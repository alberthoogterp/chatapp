<?php
namespace App\Classes;
use App\Classes\AccountInput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class InputValidation{
    
    private static function makeValidator(array $data, array $rules, array $customError = []):array{
        $validator = Validator::make($data, $rules, $customError);
        if($validator->fails()){
            return $validator->errors()->all();
        }
        return [];
    }

    public static function validateLogin(AccountInput $input):array{
        $username = $input->getUsername();
        $password = $input->getPassword();

        $data = [
            "username"=>$username,
            "password"=>$password,
        ];
        $loginRules = [
            "username"=>[
                "required",
                "string",
                "exists:users,username"
            ],
            "password"=>[
                "required",
                "string"
            ]
        ];
        return self::makeValidator($data, $loginRules);
    }

    public static function validateAccountCreation(AccountInput $input):array{
        $email = $input->getEmail();
        $username = $input->getUsername();
        $password = $input->getPassword();
        $passwordConfirm = $input->getPasswordConfirm();
        $data = [
            "email"=>$email,
            "username"=>$username,
            "password"=>$password,
            "passwordConfirm"=>$passwordConfirm
        ];
        $accountCreationRules = [
            "email"=>[
                "required",
                "email",
                "unique:users,email"
            ],
            "username"=>[
                "required",
                "string",
                "alpha_num",
                "min:3",
                "unique:users,username"
            ],
            "password"=>[
                "required",
                "string",
                "min:8",
                "max:255",
                "ascii",
                "regex:/^(?=.{8,255}$)((.)(?!\2{3})|(?!\2))+$/",
                Password::min(8)->uncompromised()
            ],
            "passwordConfirm"=>[
                "required",
                "same:password"
            ]
        ];
        $customErrors = ["password.regex"=>"No 3 consecutive same characters allowed."]; 

        return self::makeValidator($data, $accountCreationRules, $customErrors);
    }

    public static function validateServerCreation(String $serverName):array{
        $data = ["servername"=>$serverName];
        $serverCreationRules = [
            "servername"=>[
                "required",
                "string",
                "regex:/^[A-Za-z0-9\s]+$/",
                "min:3",
                "max:255",
                "unique:servers,name"
            ]
        ];
        $customError = ["servername.regex"=>"Servername can only contain numbers, letters and spaces."];
        return self::makeValidator($data, $serverCreationRules, $customError);
    }
}
?>