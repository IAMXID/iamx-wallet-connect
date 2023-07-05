<?php

namespace IAMXID\IamxWalletConnect\Http\Controllers;

use App\Models\User;
use IAMXID\IamxWalletConnect\Models\IamxIdentityAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class WalletConnectController extends Controller
{
    public function connectIdentity(Request $request) {

        $user = User::updateOrCreate(
            [
                'iamx_vuid' => $request->data['vUID']['id']
            ],[
                'name' => $request->data['person']['firstname'].' '.$request->data['person']['lastname'],
                'email' => $request->data['vUID']['id'].'@iamx.id',
                'password' => Hash::make($request->data['did'].$request->data['person']['firstname'].$request->data['person']['lastname'])
            ]
        );

        // Insert wallet data into table iamx_identity_attributes
        foreach ($request->data as $parentKey => $parentValue) {
            if (is_array($parentValue)) {
                $index = 0;
                foreach ($parentValue as $childKey => $childValue) {
                    if (is_array($childValue)) {
                        foreach ($childValue as $childChildKey => $childchildValue) {
                            if($childchildValue) {
                                IamxIdentityAttribute::updateOrCreate(
                                    [
                                        'user_id' => $user->id,
                                        'category' => $parentKey,
                                        'attribute_name' => $childChildKey,
                                        'element_number' => $index
                                    ],
                                    [
                                        'attribute_value' => Crypt::encryptString($childchildValue)
                                    ]
                                );
                            }
                        }
                        $index++;

                    } else {
                        if($childValue) {
                            IamxIdentityAttribute::updateOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'category' => $parentKey,
                                    'attribute_name' => $childKey
                                ],
                                [
                                    'attribute_value' => Crypt::encryptString($childValue)
                                ]
                            );
                        }
                    }

                }
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if(Auth::check()) {
            return $user;
        } else {
            return 'User is not logged in';
        }
    }

    public function disconnectIdentity(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return "logged out";
    }

    public function getIdentityScope() {
        return env('IAMX_IDENTITY_SCOPE');
    }
}
