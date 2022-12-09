<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductAddedSuccessfully;
use Exception;

class ProductController
{
    public function storeInFirestore() {
        //Suppose this is the request coming to Mr. Ibrahim
        $data = [
            'producttype' => "Cheese",
            'productcategory' => "Sandwich"
        ];      

        // suppose that this email is the email of the software:
        $email = "ibrahimrestaurant@res.com";

        try {

            $connectToFirebase = app('firebase.firestore')->database()->collection('Products')->newDocument();
            $connectToFirebase->set([
                'producttype' => $data["producttype"],
                'productcategory' => $data["productcategory"]
            ]);

            //Because we are in a testing mode this email notification will be sent to Mailtrap which i configured in .env file
            //Signed in to Mailtrap using my GitHub account
            Notification::route("mail", $email)
                ->notify(new ProductAddedSuccessfully());

            return redirect('/')->with('created' , 'Product Added Successfuly');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect('/')->with('failed' , 'An exception occured, Please review laravel.logs for more information');
        }
       
    }
}