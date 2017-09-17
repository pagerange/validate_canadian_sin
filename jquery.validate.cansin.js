 /**
  * Validate Canadian SIN
  * Uses LHUN/Mod-10
  * @author Steve George <steve@pagerange.com>
  * @updated 2017-09-17
  */

 jQuery.validator.addMethod("canSin", function (value, element) {
    var double = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9],
        total = 0,
        digit,
        sin = value.toString().replace(/\D/g,'');
    for(var i = 0; i < sin.length; i++) {
        digit = parseInt(sin.charAt(i));
        total += (i % 2) ? double[digit] : digit ;
    }
    return (parseInt(sin) && total % 10 == 0) ? true : this.optional(element);
 }, "Please enter a valid Canadian Social Insurance Number");
