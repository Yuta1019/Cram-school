<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\MailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AiMailController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 講師ロールのユーザーはアクセスを拒否する
    private function checkRole()
    {
        if (auth()->user()->role === 'teacher') {
            abort(403, 'この操作は受付・管理者のみ利用できます。');
        }
    }

    // AIメールページのパスワード認証が済んでいるか確認する
    private function isVerified()
    {
        return session('ai_mail_auth') === true;
    }

    // ChatGPT APIを呼び出して結果を返す
    private function callOpenAI(string $prompt)
    {
        $apiKey = env('OPENAI_API_KEY');

        // APIキーが空だったらエラーを返す
        if (empty($apiKey)) {
            return ['error' => 'OpenAI APIキーが設定されていません。'];
        }

        // 送るデータをJSON形式にまとめる
        $requestBody = json_encode([
            'model'      => 'gpt-4o-mini',
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 1000,
        ]);

        // OpenAIのAPIに接続してデータを送る
        $curlHandle = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_POST,           true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,     $requestBody);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ]);
        $responseBody = curl_exec($curlHandle);
        $httpCode     = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        $curlError    = curl_error($curlHandle);
        curl_close($curlHandle);

        // 通信エラーが起きた場合
        if ($curlError) {
            return ['error' => '通信エラー：' . $curlError];
        }

        // APIがエラーを返した場合（200以外）
        if ($httpCode !== 200) {
            return ['error' => 'APIエラー（' . $httpCode . '）。APIキーを確認してください。'];
        }

        // 受け取ったJSONから生成された文章を取り出す
        $decodedApiResponse = json_decode($responseBody, true);
        $generatedText      = $decodedApiResponse['choices'][0]['message']['content'] ?? '';

        return ['text' => trim($generatedText)];
    }

    // AIメール作成ページ表示（受付・管理者のみ）
    // アクセスのたびに ai_mail_verified フラグを pull（取り出し＆削除）して確認する
    // フラグがなければパスワード確認画面を表示し、ai_mail_auth もリセットする
    public function create(Inquiry $inquiry)
    {
        $this->checkRole();

        // pull() はセッション値を取り出した瞬間に削除する
        // verifyPassword() 経由のリダイレクトでなければ null になる
        if (!session()->pull('ai_mail_verified')) {
            session()->forget('ai_mail_auth');
            return view('inquiry.ai_mail_password', compact('inquiry'));
        }

        // パスワード確認済み：このページ滞在中のみ有効なフラグを立てる
        session(['ai_mail_auth' => true]);

        // この問い合わせの送信履歴を新しい順に取得する
        $mailLogs = MailLog::with('sentBy')
                           ->where('inquiry_id', $inquiry->id)
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('inquiry.ai_mail', compact('inquiry', 'mailLogs'));
    }

    // パスワード確認処理
    public function verifyPassword(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

        $request->validate([
            'password' => 'required|string',
        ]);

        // 入力されたパスワードとDBのパスワードを照合する
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->route('ai_mail.create', $inquiry)
                ->with('auth_error', 'パスワードが正しくありません。');
        }

        // create() で pull() して使い捨てにするフラグを保存する
        session(['ai_mail_verified' => true]);

        return redirect()->route('ai_mail.create', $inquiry);
    }

    // ChatGPT APIを呼び出してメール文章を生成・添削する（AJAX用）
    public function generate(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

        // パスワード未確認の場合はエラーを返す
        if (!$this->isVerified()) {
            return response()->json(['error' => '認証が必要です。ページを再読み込みしてください。']);
        }

        // 「type」は "generate"（生成）か "proofread"（添削）のどちらか
        $request->validate([
            'type' => 'required|in:generate,proofread',
        ]);

        // typeによって、ChatGPTへの指示文（プロンプト）を変える
        if ($request->type === 'generate') {

            // 文章生成の場合
            $request->validate([
                'purpose' => 'required|string|max:200',
            ]);

            $parentName  = $inquiry->parent_name  ?? '';
            $studentName = $inquiry->student_name ?? '';
            $purpose     = $request->purpose;

            $prompt = "あなたは学習塾のスタッフです。保護者へのメール本文を作成してください。\n"
                . "保護者名：{$parentName} 様\n"
                . "生徒名：{$studentName}\n"
                . "用途：{$purpose}\n\n"
                . "条件：\n"
                . "- 丁寧な敬語で書いてください\n"
                . "- 件名は不要で、本文のみ出力してください\n"
                . "- 署名は不要です";

        } else {

            // 添削の場合
            $request->validate([
                'body' => 'required|string|max:3000',
            ]);

            $prompt = "以下のメール本文を、自然で丁寧な敬語に添削してください。\n"
                . "添削後の本文のみを出力してください。\n\n"
                . $request->body;
        }

        // ChatGPT APIを呼び出してJavaScriptにJSON形式で返す
        $result = $this->callOpenAI($prompt);
        return response()->json($result);
    }

    // メールを送信する（受付・管理者のみ）
    public function send(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

        // パスワード未確認の場合は確認画面に戻す
        if (!$this->isVerified()) {
            return redirect()->route('ai_mail.create', $inquiry)
                ->with('auth_error', 'パスワード認証が必要です。');
        }

        $request->validate([
            'subject' => 'required|string|max:200',
            'body'    => 'required|string',
        ]);

        // 送信先メールアドレスが登録されていない場合はエラーを返す
        if (empty($inquiry->parent_email)) {
            return redirect()->back()
                ->with('error', '保護者のメールアドレスが登録されていません。')
                ->withInput();
        }

        \Mail::raw($request->body, function ($message) use ($inquiry, $request) {
            $message->to($inquiry->parent_email)
                    ->subject($request->subject);
        });

        // 送信内容をログとして保存する
        MailLog::create([
            'inquiry_id' => $inquiry->id,
            'sent_by'    => auth()->id(),
            'to_email'   => $inquiry->parent_email,
            'subject'    => $request->subject,
            'body'       => $request->body,
        ]);

        // 送信後はフラグを削除する（次回アクセス時に再度パスワードが必要になる）
        session()->forget('ai_mail_auth');

        return redirect()->route('inquiry.show', $inquiry)
            ->with('success', 'メールを送信しました。');
    }
}
