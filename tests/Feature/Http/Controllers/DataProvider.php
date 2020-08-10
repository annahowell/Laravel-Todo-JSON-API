<?php

namespace Tests\Feature\Http\Controllers;

class DataProvider
{
    public function userPostLoginInputValidation(): array
    {
        return [
            'email, \'\''                  => ['email', '',],
            'invalid-email-field, \'\''    => ['invalid-name-field', '',],
            'password, \'\''               => ['password', '',],
            'invalid-password-field, \'\'' => ['invalid-password-field', '',],
        ];
    }


    public function userPostStringSignUpInputValidation(): array
    {
        return [
            'displayname, \'\''               => ['displayname', '',],
            'invalid-displayname-field, \'\'' => ['invalid-displayname-field', '',],
            'email, \'\''                     => ['email', '',],
            'invalid-email-field, \'\''       => ['invalid-name-field', '',],
            'password, Not long enough'       => ['password', 'n0tLong!',],
            'password, Missing uppercase'     => ['password', 'n0uppercase!',],
            'password, Missing lowercase'     => ['password', 'N0LOWERCASE!',],
            'password, Missing special char'  => ['password', 'n0specialChar',],
            'invalid-password-field, \'\''    => ['invalid-password-field', '',],
        ];
    }


    public function userPostIntSignUpInputValidation(): array
    {
        return [
            'displayname, 1'           => ['displayname', 1,],
            'email, 1'                 => ['email', 1,],
            'password, 1'              => ['password', 1,],
            'password_confirmation, 1' => ['password_confirmation', 1,],
        ];
    }
}
