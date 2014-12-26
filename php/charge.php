<?php
  require_once('./config.php');

  $token  = $_POST['stripeToken'];  // These are obtain from a form submit with method "POST"
  $email  = $_POST['stripeEmail'];
  
  //Most calls to Stripe or third party APIs should be in a try/catch incase they fail for some reason
  try{ 
    // Create a customer, by passing card and token, the token is saved to the customer
    // Stripe will also do a $1 authorization to verify the card. 
    // See https://support.stripe.com/questions/why-does-my-customer-see-a-1-charge-when-their-payment-failed
    // For info about that $1 auth
    $customer = Stripe_Customer::create(array(
        'email' => $email,
        'card'  => $token
    ));
    
    // Here you can save your customer id. 
    // If you hit this line, no errors occured.
  }
  // Trys need to have a corresponding catch statement
  // A try can have multiple catch statements depending on the type of error that can occur
  catch(Stripe_CardError $e) // Catching a card error, like the card was declined
  {
    echo '<h1>Uh oh an error occured, your card wasn\'t charged</h1><p>' . $e->getMessage() . '</p>'
  }
  catch(Stripe_Error $e) // Some more generic Stripe error occured. 
  {
    echo '<h1>Uh oh an error occured, your card wasn\'t charged</h1><p>' . $e->getMessage() . '</p>'
  }

  try{
     $charge = Stripe_Charge::create(array(
      'customer' => $customer->id,
      'amount'   => 5000,
      'currency' => 'usd',
      'description' => 'Widget, Qty 1'
    ));

    echo '<h1>Successfully charged $50.00!</h1>';
  }
  catch(Stripe_CardError $e) // Catching a card error, like the card was declined
  {
    echo '<h1>Uh oh an error occured, your card wasn\'t charged</h1><p>' . $e->getMessage() . '</p>'
  }
  //If another error occured, like an invalid request, in this case we wouldn't have caught it. 

?>
