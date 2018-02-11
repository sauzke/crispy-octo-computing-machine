<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotAiController extends Controller
{
    private $realFlag = false;
    private $safeFlag = false;
    private $optionsFlag = false;
    private $casual = array(
        "How's your day?",
        "What kind of music do you like?",
        "Have you heard about that car in space?"
    );

    public function read(Request $request){
        $name = $request->name;
        $response = $this->logic($name);
        return response()->json(array('response' => $response));
    }

    private function logic($request){
        $response = "";
        $text = str_replace("'","",$request);
        $text = strtolower($text);

        if($this->realFlag){
            if(strpos($text, 'continue') !== false){
                $this->realFlag = false;
                $response = "Ok, I will keep you company.";
            }
            else if(strpos($text, 'person') !== false){
                $this->realFlag = false;
                $response = "Fraser Valley Help Line: 604-852-9099";
            }
            else{
                $response = "I'm sorry, I don't understand.";
            }
        }

        else if($this->safeFlag){
            if(strpos($text, 'yes')){
                $this->safeFlag = false;
                $response = "Dialing 911 now...";
            }
            else if(strpos($text, 'no')){
                $this->safeFlag = false;
                $response = "Ok, I will keep you company.";
            }
            else{
                $response = "I'm sorry, I don't understand.";
            }
        }

        else if($this->optionsFlag){
            $this->optionsFlag = false;
            if(strpos($text, 'human')){
                $this->realFlag = true;
            }
            else if(strpos($text, 'counselor')){
                $response = "Phone: 604-827-5180";
            }
            else if(strpos($text, 'support')){
                $response = "Book Online at UBC Counselling Services";
            }
            else{
                $response = "I'm sorry, I don't understand.";
            }
        }

        else if(strpos($text, 'not sure') !== false or strpos($text, 'dont know') !== false){
            $response = "Would you rather continue anonymously exploring your options with me or talk to a real person? [Continue/Person]";
            $this->realFlag = true;
        }

        else if(strpos($text, 'afraid') !== false or strpos($text, 'scared') !== false){
            $response = "I’m sorry this happened to you. It’s not your fault and it shouldn’t have happened. Are you in a safe place now? [Yes/No]";
            $this->realFlag = true;
        }

        else if(strpos($text, 'my options') !== false){
            $response = "Sure, let’s talk about options.  I can tell you about resources you can access and what services they will provide.\nWould you like to talk to a real person? [Person]\nWould you like to book an appointment with a counselor? [Counselor]\nWould you like to get in touch with a support group? [Support]";
            $this->optionsFlag = true;
        }
        else{
            $random = rand ( 0 , 2 );
            $response = $this->casual[$random];
        }

        return $response;
    }
}

?>