<?php
return [
    //An array mapping shibboleth fields (lhs) to user table fields (rhs)
    //All fields on RHS MUST exist on user table
    'token_map' => [
        'cn' => 'fullname',
        'mail' => 'email',
        'glid' => 'name',
        'ufid' => 'ufid',
    ],

    //field that is used to search for a user in the user table
    'unique_field'=>'ufid',
];

