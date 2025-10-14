<?php
//バリデーション関数

//エラーメッセージ追加処理
function addError(&$errors, $fieldName, $errorMessage) {
    if ($errorMessage !== null) {
        if (!isset($errors[$fieldName])) {
            $errors[$fieldName] = [];
        }
        $errors[$fieldName][] = $errorMessage;
    }
}

//空欄チェック
function isRequired($value, $fieldName)  {
    //!empty($value) だと0やfalseもエラーになるので
    if (is_null($value) || trim($value) === '') {
        return "{$fieldName}は必須項目です。";
    }
    return  null;
}
//最低文字数バリデーション
function isMinLength($value, $minLength, $fieldName) {
    if (strlen($value) < $minLength) {
        return "{$fieldName}は{$minLength}文字以上で入力してください。";
    }
    return null;
}
//最大文字数バリデーション
function isMaxLength($value, $maxLength, $fieldName) {
    if (strlen($value) > $maxLength) {
        return "{$fieldName}は{$maxLength}文字以内で入力してください。";
    }
    return null;
}
//Email形式チェック
function isEmailFormat ($value, $fieldName) {
    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "{$fieldName}の形式が正しくありません。";
    }
    return null;
}
//ユニークキーチェック
function  isUnique($count, $fieldName) {
    if ($count > 0) {
        return "{$fieldName}はすでに登録されています。";
    }
    return null;
}


?>
