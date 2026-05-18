<?php

namespace App\Http\Controllers;

use App\Inquiry;
use Illuminate\Http\Request;

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
        if (auth()->user()->role === 'instructor') {
            abort(403, 'この操作は受付・管理者のみ利用できます。');
        }
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
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $requestBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ]);
        $responseBody = curl_exec($ch);
        $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError    = curl_error($ch);
        curl_close($ch);

        // 通信エラーが起きた場合
        if ($curlError) {
            return ['error' => '通信エラー：' . $curlError];
        }

        // APIがエラーを返した場合（200以外）
        if ($httpCode !== 200) {
            return ['error' => 'APIエラー（' . $httpCode . '）。APIキーを確認してください。'];
        }

        // 受け取ったJSONから生成された文章を取り出す
        $data = json_decode($responseBody, true);
        $text = $data['choices'][0]['message']['content'] ?? '';

        return ['text' => trim($text)];
    }

    // AIメール作成ページ表示（受付・管理者のみ）
    public function create(Inquiry $inquiry)
    {
        $this->checkRole();

        return view('inquiry.ai_mail', compact('inquiry'));
    }

    // ChatGPT APIを呼び出してメール文章を生成・添削する（AJAX用）
    public function generate(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

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

        // 予約確定後はお問い合わせ詳細ページへ遷移する
        return redirect()->route('inquiry.show', $inquiry)
            ->with('success', 'メールを送信しました。');
    }
}
