/**
  * Validate Canadian SIN - plain javascript
  * Uses LUHN/Mod-10
  * @author Steve George <steve@pagerange.com>
  * @updated 2017-09-17
 */
function validateSin (num) {
    var sin = num.toString().replace(/\D/g,'');
    var offset = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];
    var digit;
    var total = 0;
    for(var i = 0; i < sin.length; i++) {
        digit = parseInt(sin.charAt(i));
        total += (i % 2) ? offset[digit] : digit ;
    }
    return (parseInt(sin) && total % 10 == 0) ? true : false;
};