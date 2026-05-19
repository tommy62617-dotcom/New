<?php



namespace App\Http\Controllers;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Services\PasswordService; // 引入剛剛寫好的 Service​



class PasswordController extends Controller

{

    protected $passwordService;




    // 透過建構子自動注入 Service​

    public function __construct(PasswordService $passwordService)

    {

        $this->passwordService = $passwordService;

    }




    /**​

     * 處理產生密碼的請求​

     */

    public function generate(Request $request)

    {

        // 1. 驗證前端傳來的參數 (GET 請求的 Query String)​

        // nullable 代表前端可以不傳，我們會給預設值​

        $validated = $request->validate([

            'length' => 'nullable|integer|min:4|max:64',

            'symbols' => 'nullable|boolean',

        ]);



        // 取得參數，若無則給予預設值 (長度預設 12，不含符號)​

        $length = $validated['length'] ?? 12;

        

        // 注意：前端傳來的 boolean 可能是字串 'true'，Laravel 的 filter_var 可以幫忙轉型​

        $symbols = filter_var($request->query('symbols', false), FILTER_VALIDATE_BOOLEAN);




        // 2. 呼叫 Service 處理核心邏輯​

        $password = $this->passwordService->generate($length, $symbols);



        // 3. 回傳標準化 JSON 格式​

        return response()->json([

            'success' => true,

            'message' => '密碼產生成功',

            'data' => [

                'password' => $password,

                'length' => $length,

                'has_symbols' => $symbols

            ]

        ], 200);

    }

}

