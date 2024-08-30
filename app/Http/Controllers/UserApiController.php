<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Mail\NewUserRegistred;
use App\Role;
use App\User;
use Mail;
use Illuminate\Support\Str;

class UserAPiController extends Controller
{
    protected $messages = [
        'contacts.*.value.required' => 'Contact is required.',
        'contacts.*.contact_type_id.required' => 'Contact type is required.',
        'username.white_space' => 'Username cannot contains space.',
        'username.special_chars' => 'Username cannot contains special characters.'
    ];

    protected $rules;

    public function clients(\Request $request)
    {

        $user = \App\User::getUserLogged();

        $users = \App\User::whereHas("roles", function ($q) {
            $q->where("name", "=", "Client");
        })
            ->where('group_id', $user->group->id)
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
        }

        return response()->json($users);
    }

    public function admins()
    {

        $user = \App\User::getUserLogged();

        $users = \App\User::whereHas("roles", function ($q) {
            $q->where("name", "=", "Admin");
        })
            ->where('group_id', $user->group->id)
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
        }

        return response()->json($users);
    }

    public function exporters()
    {

        $user = \App\User::getUserLogged();

        $users = \App\User::whereHas("roles", function ($q) {
            $q->where("name", "=", "Exporter");
        })
            ->where('group_id', $user->group->id)
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
        }

        return response()->json($users);
    }

    public function carriers(\Request $request)
    {
        $user = \App\User::getUserLogged();

        $users = \App\User::whereHas("roles", function ($q) {
            $q->where("name", "=", "Carrier");
        })
            ->where('group_id', $user->group->id)
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->whereNull('deleted_at')
            ->get();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
        }

        return response()->json($users);
    }

    public function nonAdmin(\Request $request)
    {
        $user = \App\User::getUserLogged();
        $users = \App\User::with('contracts.contract_services')->whereHas("roles", function ($q) {
            $q->where("name", "!=", "Admin");
        })->where('group_id', $user->group->id)
            ->whereNull('deleted_at')->get();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
        }

        return response()->json($users);
    }

    public function allUsers(\Request $request)
    {

        $user = \App\User::getUserLogged();

        $users = \App\User::with('contracts.contract_services', 'group')
            ->has('roles')
            ->where('group_id', $user->group->id)
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            //->whereNull('deleted_at')
            ->get();
            
        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;

            $firstContact = $user->contacts->toArray();
            $user->contact = $firstContact ? $firstContact : null;
        }

        return response()->json($users);
    }

    public function getByRole(\Request $request)
    {
        $user = User::getUserLogged();
        
        $users = User::whereHas("roles", function ($q) {
            $q->whereNotIn("roles.id", [1, 2]);
        })
        ->where(function ($q) use ($user) {
            $q
                ->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
        ->get();

        $roles = array();

        foreach ($users as &$user) {
            $user->role = $user->roles()->first()->name;
            $role_slug = Str::slug($user->roles()->first()->name, "_");
            $role_slug = preg_replace('/certificate_of_/', '', $role_slug) . '_id';
            if (!array_key_exists($role_slug, $roles)) $roles[$role_slug] = array();
            $roles[$role_slug][] = $user;
        }

        return response()->json($roles);
    }

    public function get(\Request $request, $id)
    {
        $user = \App\User::find($id);

        $user->contacts = $user->contacts->toArray();
        $user->role = $user->roles()->first()->id;
        $user->banks;

        return response()->json($user);
    }

    public function post(\Request $request)
    {
        $userLogged = \App\User::getUserLogged();
        $user = new \App\User();
        $user->token = bin2hex(random_bytes(8));

        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user->name            = \Request::input('name');
        $user->username        = \Request::input('username');
        $user->email           = \Request::input('email');
        $user->country         = \Request::input('country');
        $user->neighborhood    = \Request::input('neighborhood');
        $user->city            = \Request::input('city');
        $user->state           = \Request::input('state');
        $user->zip             = \Request::input('zip');
        $user->number          = \Request::input('number');
        $user->address_1       = \Request::input('address_1');
        $user->address_2       = \Request::input('address_2');
        $user->profile_picture = \Request::input('profile_picture');
        $user->cnpj            = \Request::input('cnpj');
        $user->tax_substitution            = \Request::input('tax_substitution');
        $user->state_registration            = \Request::input('state_registration');

        $children = \Request::input('children');
        if (is_array($children)) {
            $user->children =  $children;
        }

        if ($userLogged->group->id != 1) {
            $user->group_id = $userLogged->group->id;
        } else {
            $user->group_id = \Request::input('group_id');
        }

        $user->save();
        $user->roles()->save(\App\Role::find(\Request::input('role')));

        if (\Request::input('banks.beneficiary_name') || \Request::input('banks.beneficiary_swift') || \Request::input('banks.beneficiary_account')) {
            $bank = new \App\Bank();

            $bank->beneficiary_name = \Request::input('banks.beneficiary_name');
            $bank->beneficiary_swift = \Request::input('banks.beneficiary_swift');
            $bank->beneficiary_account = \Request::input('banks.beneficiary_account');
            $bank->entity_id = $user->id;
            $bank->entity = 'Users';

            $bank->save();
        }

        foreach (\Request::input('contacts') as $contact) {
            $tmp = new \App\Contact();

            $tmp->contact_type_id = $contact['contact_type_id'];
            $tmp->value = $contact['value'];
            $tmp->user_id = $user->id;

            $tmp->save();
        }

        /*try {
            Mail::to($user->email)->send(new NewUserRegistred([
                    'name' => $user->name,
                    'link' => "change-password?token={$user->token}&email={$user->email}"
                ]));
        } catch (\Exception $e) {
            return response()->json(['success' => 'Added']);
        }*/

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://agricolalucas.com.br/popcorn/public/api/sendmail/' . $user->id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
        $headers[] = 'Accept-Encoding: gzip, deflate';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cookie: TS01e85bed=01f0e93131bae0478169bb1b9d694fe63d0bf3b9ff430c0c20b12f1dbf3e1f4dc9c5e97c742e791afb0750fb77d8c30901c67ac991; 4a1a8ad7fbf18ee2288af1551e5d9b13=bed3431eab87ace6e5a87230e18225ef; 563dd6e645a8bad0cd7c2f736c39344d=pt-BR; __utma=80813575.385846376.1560947454.1560947454.1560947454.1; __utmc=80813575; __utmz=80813575.1560947454.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); XSRF-TOKEN=eyJpdiI6Imp3ejgwa1pCVTliOWp1cGFFcUVEcnc9PSIsInZhbHVlIjoiXC9hK1wvRTNtV3JqcFV2QW1VTTJCMVVCYW1IV09DdVNURXhnYXJOUlduTVBvYzNsVjVSMXh6czBYZzhTcVZ5bGhvIiwibWFjIjoiYzIzNDVlMmVkNWRhOGNhY2M1MjJkMWUwODU3ZGFiNzdhOGNlMTIzY2RjYjUwZWYwYzFmNmE5MDMxNjU5ZWVhZCJ9; popcorn_session=eyJpdiI6IjFTeEF6enk3MnllVjl0RHF1Ykc2N0E9PSIsInZhbHVlIjoiaG9cL0dSV1Z3Q21BbmNMXC9iQnlMclwvcHFrTXIzOHFLaHN5S3VmWHJCdmJTZ2FhNUJKbnZSd3h0MVwvb3VuN3NIRjQiLCJtYWMiOiI1MGJjYzIwZTZiZjFmNzc2NDQyZDU4NGNmNTVjZGUxNmY3NzMyNTczYzc4NGQ5ZTljYmEwY2ZhNTdiNDdjM2RkIn0%3D';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return response()->json(['success' => 'Client is successfully added.', 'link' => 'http://agricolalucas.com.br/popcorn/public/api/sendmail/' . $user->id, 'content' => $result]);
    }

    public function sendEmail(\Request $request, $user_id)
    {
        $user = \App\User::find($user_id);

        $mail_body = new NewUserRegistred([
            'name' => $user->name,
            'username' => $user->username,
            'link' => "http://popcorn.agricolalucas.com.br/change-password?token={$user->token}&email={$user->email}"
        ]);
        try {
            $mail = Mail::to($user->email)->send($mail_body);
        } catch (\Swift_TransportException $e) {
            dd($e);
        } catch (\Exception $e) {
            dd($e);
        }

        return response()->json('sendEmail: ' . $user_id);
    }

    public function sendEmailRecovery(\Request $request, $user_email)
    {
        $user = \App\User::where('email', $user_email)->first();

        $token = md5(microtime());
        $user->token = $token;

        $user->save();

        $mail_body = new NewUserRegistred([
            'name' => $user->name,
            'username' => $user->username,
            'link' => "http://popcorn.agricolalucas.com.br/change-password?token={$token}&email={$user->email}"
        ]);
        try {
            $mail = Mail::to($user->email)->send($mail_body);
        } catch (\Swift_TransportException $e) {
            dd($e);
        } catch (\Exception $e) {
            dd($e);
        }

        return '<script>alert(\'An email with a password recovery was sent to your email\');</script>';
        //return response()->json('sendEmail: ' . $user_id);
    }

    public function sendEmailResend(\Request $request, $user_id)
    {
        $user = \App\User::find($user_id);

        $user->token = md5(microtime());

        $user->save();

        $mail_body = new NewUserRegistred([
            'name' => $user->name,
            'username' => $user->username,
            'link' => "http://popcorn.agricolalucas.com.br/change-password?token={$user->token}&email={$user->email}"
        ]);
        try {
            $mail = Mail::to($user->email)->send($mail_body);
        } catch (\Swift_TransportException $e) {
            dd($e);
        } catch (\Exception $e) {
            dd($e);
        }

        return '<script>alert(\'An email with a password recovery was sent to client account\');</script>';
        //return response()->json('sendEmail: ' . $user_id);
    }

    public function put(\Request $request)
    {

        $userLogged = \App\User::getUserLogged();
        $user = \App\User::find(\Request::input('id'));
        $user->token = null;

        $validator = \Validator::make(\Request::all(), $this->getRules($user->id), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user->name            = \Request::input('name');
        $user->username        = \Request::input('username');
        $user->email           = \Request::input('email');
        $user->country         = \Request::input('country');
        $user->neighborhood    = \Request::input('neighborhood');
        $user->city            = \Request::input('city');
        $user->state           = \Request::input('state');
        $user->zip             = \Request::input('zip');
        $user->number          = \Request::input('number');
        $user->address_1       = \Request::input('address_1');
        $user->address_2       = \Request::input('address_2');
        $user->profile_picture = \Request::input('profile_picture');
        $user->cnpj            = \Request::input('cnpj');
        $user->tax_substitution = \Request::input('tax_substitution');
        $user->state_registration   = \Request::input('state_registration');

        if (\App\Role::find(\Request::input('role'))) {
            $user->roles()->detach();
            $user->roles()->save(\App\Role::find(\Request::input('role')));
        }

        if ($userLogged->group->id != 1) {
            $user->group_id = $userLogged->group->id;
        } else {
            $user->group_id = \Request::input('group_id');
        }

        $children = \Request::input('children');
        if (is_array($children)) {
            $user->children =  $children;
        }

        if (\Request::input('password')) $user->password = bcrypt(\Request::input('password'));

        $user->save();

        if (\Request::input('banks') != NULL || \Request::input('beneficiary_name')) {
            $bank = new \App\Bank();

            if (\Request::input('banks') != NULL) {
                $bank = $bank->where('entity_id', $user->id)->where('entity', 'Users')->first();
            }

            $bank->beneficiary_name = \Request::input('banks.beneficiary_name') ?: \Request::input('beneficiary_name');
            $bank->beneficiary_swift = \Request::input('banks.beneficiary_swift') ?: \Request::input('beneficiary_swift');
            $bank->beneficiary_account = \Request::input('banks.beneficiary_account') ?: \Request::input('beneficiary_account');
            $bank->entity = 'Users';
            $bank->entity_id = $user->id;

            $bank->save();
        }

        foreach ($user->contacts as $contact) {
            $contact->delete();
        }

        if (\Request::input('contacts')) {
            foreach (\Request::input('contacts') as $contact) {
                $tmp = new \App\Contact();

                $tmp->contact_type_id = $contact['contact_type_id'];
                $tmp->value = $contact['value'];
                $tmp->user_id = $user->id;

                $tmp->save();
            }
        }

        return response()->json(['success' => 'Client is successfully updated.']);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'This account cannot be founded.']);
        }

        if ($user->id == 1) {
            return response()->json(['error' => 'You cannot delete this account.']);
        }

        $role = $user->roles()->where('user_id', $user->id)->first();

        if ($role) {
            $user->roles()->detach($role->id);
        }

        Contact::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['success' => 'Client is sucessfully deleted.']);
    }

    protected function getRules($id)
    {

        $userLogged = \App\User::getUserLogged();

        $rule = [
            'name' => 'required|max:80|min:5',
            'username' => [
                'required',
                'white_space',
                'special_chars',
                \Illuminate\Validation\Rule::unique('users')->ignore($id),
                'min:5',
                'max:20'
            ],
            'email' => [
                'required',
                //\Illuminate\Validation\Rule::unique('users')->ignore('comercial@agricolalucas.com.br', 'email')->ignore($id),
                'email'
            ],
            'country' => 'required',
            'contacts.*.value' => 'required',
            'contacts.*.contact_type_id' => 'required'
        ];

        if (\Request::input('password') || \Request::input('password_confirmation')) {
            $rule['password'] = [
                'required'
            ];

            $rule['password_confirmation'] = [
                'required',
                'same:password'
            ];
        }

        if ($userLogged->group->id == 1) {
            $rule['group_id'] = [
                'required'
            ];
        }

        return $rule;
    }
}
