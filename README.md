# Validate Canadian Social Insurance Number

This small script adds a validator for the Canadian SIN to the jQuery.validate plugin.  Also included are version in plain javascript and PHP.

The jQuery Validate plugin can be found here: https://jqueryvalidation.org/

## Instructions

### For jQuery.validate plugin

Load `jquery.validate.cansin.js` after you have loaded jQuery and jQuery.validate.

Validate your sin field as you would any other form field with jQuery.validate.  For example, for a field named `sin` that is also required, the rule would look like this:

```javascript

    $('#my_form').validate({
        rules: {
            "sin": { 
                required:true,
                canSin:true 
            },
         }  
     )};

```

### For Vlucas\Valtron\Validator class (php)

Copy the code in `valtron.validator.cansin.php` and paste it into your code immediately after instantiating the validator.  For example...

```php

    $v = new Valtron\Validator();

    $v->addRule('canSin', function($field, $value) {
    $sin = preg_replace('/[^0-9]/s', '', $value);
    $doubled = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];
    $total = 0;
    for($i = 0; $i < strlen($sin); $i++) {
        $digit = (int) $sin[$i];
        $total += ($i % 2) ? $doubled[$digit] : $digit ;
    }
    return ((int) $sin && $total % 10 === 0) ? true : false;
}, 'Please enter a valid Canadian Social Insurance Number');


```

Then, when setting your rules (for a form field named `sin`):

```php

    $v->rule('canSin', 'sin');

    // Or to set a custom message with the rule...

    $v->rule('canSin', 'sin')->message('Hey, enter a valid SIN!');


```



## Notes

Validation follows this basic algorithm:

1. Multiply the SIN by `121212121`.
2. Every SIN digit in an odd position gets multiplied by 1.
3. Every SIN digit in an even position gets multiplied by 2.  If, when multiplying by 2, the product has two digits (eg: 2 * 6 = 12), then add the two digits in the product to get a single digit result:  1 + 2 = 3.
4. Add all the resulting numbers together.
5. If the final sum can be evenly divided by 10, the number is a valid Canadian SIN.

This is not as complicated as it seems, as there are only five digits that will need to use the added step of adding individual digits of the product together, so we can pre-calculate them all and put them in an array.


```javascript

    function (value, element) {

    // Must treat value as string in order to access
    // each digit by index... strip out non-numerics
    // while we're at it
    var sin = value.toString().replace(/\D/g,'');

    // There are a limited number of possible values for the "doubling"
    // algorithm, so we can calculate tham all up front
    // If the product is a single digit, use it.
    // If it's a double digit... eg 2 * 6 = 12
    // Then add the two single digits to get a single digit:
    // eg:  2 * 6 = 12 = 1 + 2 = 3
    // double[0] = 2 * 0 = 0;
    // double[1] = 2 * 1 = 2;
    // double[2] = 2 * 2 = 4;
    // double[3] = 2 * 3 = 6;
    // double[4] = 2 * 4 = 8;
    // double[5] = 2 * 5 = 10 = 1 + 0 = 1;
    // double[6] = 2 * 6 = 12 = 1 + 2 = 3;
    // double[7] = 2 * 7 = 14 = 1 + 4 = 5;
    // double[8] = 2 * 8 = 16 = 1 + 6 = 7;
    // double[9] = 2 * 9 = 18 = 1 + 8 = 9;
    // or:
    var double = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];

    // Individual digit from sin string
    var digit;

    // Running total of sum of digits
    var total = 0;

    for(var i = 0; i < sin.length; i++) {

         // now we need the digit as an integer
        digit = parseInt(sin.charAt(i));

        // Digit in odd position = digit * 1 (or just the digit)
        // Digit in even position = digit * 2 (see doubled array)
        total += (i % 2) ? double[digit] : digit ;

    }

    // Return true or false (as required by jQuery validate)
    // True that... sin has an integer value other than 0
    // AND
    // total mod 10 === 0
    return (parseInt(sin) && total % 10 == 0) ? true : this.optional(element);
 }

 ```

 ## License

 I saw the inspiration for this in a code sample online a while back, but can't remember where or find it anymore.  That snippet was released under the "Do What The Fuck You Want To Public License", so I'll leave this collection under a similar license.

 DO WHATEVER YOU WANT TO PUBLIC LICENSE  
 Version 1, September 2017 

 Everyone is permitted to copy and distribute verbatim or modified 
 copies of this license document, and changing it is allowed as long 
 as the name is changed. 

 DO WHATEVER YOU WANT TO PUBLIC LICENSE 
 TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

 0. You just DO WHATEVER YOU WANT TO.

 http://www.wtfpl.net/about/




```