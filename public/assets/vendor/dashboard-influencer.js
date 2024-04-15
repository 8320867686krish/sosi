$(function() {
    "use strict";
    // ============================================================== 
    // Gender Js
    // ============================================================== 

    Morris.Donut({
        element: 'gender_donut',
        data: [
            { value: 60, label: 'Female' },
            { value: 40, label: 'Male' }

        ],

        labelColor: '#5969ff',
        colors: [
            '#5969ff',
            '#ff407b',

        ],



        formatter: function(x) { return x + "%" }
    });

    // ============================================================== 
    //  chart bar horizontal
    // ============================================================== 
   


});