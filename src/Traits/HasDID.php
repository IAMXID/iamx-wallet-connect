<?php

namespace IAMXID\IamxWalletConnect\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

trait HasDID {

    public function getDIDAttribute($category, $attributeName, $userId, $elementNumber = null) {

        if ($elementNumber) {
            $attribute = DB::table('iamx_identity_attributes')
                ->select('attribute_value')
                ->where('user_id', $userId)
                ->where('category', '=', $category)
                ->where('element_number', '=', $elementNumber)
                ->where('attribute_name', '=', $attributeName)
                ->first();
        } else {
            $attribute = DB::table('iamx_identity_attributes')
                ->select('attribute_value')
                ->where('user_id', $userId)
                ->where('category', '=', $category)
                ->where('attribute_name', '=', $attributeName)
                ->first();
        }


        return Crypt::decryptString($attribute->attribute_value);
    }

    public function getDIDCategoryValues($category, $userId) {
        $categoryValues = DB::table('iamx_identity_attributes')
            ->select('element_number', 'attribute_name', 'attribute_value')
            ->where('user_id', $userId)
            ->where('category', '=', $category)
            ->whereNotIn('attribute_name', ['verification_level', 'verification_methods', 'verification_source', 'verification_timestamp'])
            ->get();

        $categoryValues->map(function ($item, $key) {
            $item->attribute_value = Crypt::decryptString($item->attribute_value);
        });

        return $categoryValues;
    }

    public function getAllDIDValues($userId) {
        $DIDValues = DB::table('iamx_identity_attributes')
            ->select('category', 'element_number', 'attribute_name', 'attribute_value')
            ->where('user_id', $userId)
            ->whereNotIn('attribute_name', ['verification_level', 'verification_methods', 'verification_source', 'verification_timestamp'])
            ->get();

        $DIDValues->map(function ($item, $key) {
            $item->attribute_value = Crypt::decryptString($item->attribute_value);
        });

        return $DIDValues;
    }

}
