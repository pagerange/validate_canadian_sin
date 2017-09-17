# Validate Canadian Social Insurance Number

This small script adds a validator for the Canadian SIN to the jQuery.validate plugin.

## instructions

Load `validate_sin.js` after you have loaded jQuery and jQuery.validate.

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

Validation follows this basic algorithm:

1. Multiply the SIN by `121212121`.
2. Every SIN digit in an odd position gets multiplied by 1.
3. Every SIN digit in an even position gets multiplied by 2.  If, when multiplying by 2, the product has two digits (eg: 2 * 6 = 12), then add the two digits in the product to get a single digit result:  1 + 2 = 3.
4. Add all the resulting numbers together.
5. If the final sum can be evenly divided by 10, the number is a valid Canadian SIN.

This is not as complicated as it seems, as there are only five digits that will need to use the added step of adding individual digits of the sum together, so we can pre-calculate them all and put them in an array.


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