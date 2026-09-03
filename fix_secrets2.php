<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

$users = User::whereNotNull('two_factor_secret')->get();
foreach ($users as $user) {
    $rawSecret = $user->getRawOriginal('two_factor_secret');
    try {
        // We know it's encrypted with Crypt::encrypt(), so it contains serialization: s:16:"MYSECRET";
        $decrypted = Crypt::decryptString($rawSecret);
        
        // If it was encrypted with Crypt::encrypt(), $decrypted will start with s: and end with ;
        if (preg_match('/^s:\d+:"(.*?)";$/', $decrypted, $matches)) {
            $actualSecret = $matches[1];
            
            // Re-encrypt it properly WITHOUT serialization
            DB::table('users')
                ->where('id', $user->id)
                ->update(['two_factor_secret' => Crypt::encryptString($actualSecret)]);
            echo "Fixed serialization for User ID {$user->id}.\n";
        } else {
            echo "User ID {$user->id} is already correctly encrypted.\n";
        }
    } catch (DecryptException $e) {
        // If it throws exception, it might be plaintext or something else.
        echo "Could not decrypt User ID {$user->id}.\n";
    }
}
echo "Done.\n";
