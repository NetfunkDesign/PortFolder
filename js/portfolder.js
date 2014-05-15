jQuery(document).ready(function() {

    jQuery('#portfolder-form').ajaxForm({

        target          :   '#portfolder-update',

        url             :   ajaxurl,

        success         :   function() {

            var messageContainer = jQuery('#portfolder-update');

            while(messageContainer.queue()>0);

            messageContainer.fadeIn(500,function() {

                jQuery(this).delay(3000).fadeOut(500);

            })

        }

    });

});

