/**
 * Select form auto submit
 */
(function() {
    "use strict";

    var select = document.getElementById("form-element-orderby");

    select.setAttribute("onchange", "orderbyform.submit();");

})();
