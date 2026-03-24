<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{
    public function testBot()
    {
        try {
            Telegram::sendMessage([
                'chat_id' => '7125153160', 
                'text' => 'សួស្តី! នេះគឺជាសារចេញពី Laravel ទៅកាន់ Medusa Order Bot 🚀'
            ]);

            return "សារត្រូវបានផ្ញើទៅកាន់ Telegram រួចរាល់ហើយ!";
        } catch (\Exception $e) {
            return "មានបញ្ហា៖ " . $e->getMessage();
        }
    }
}