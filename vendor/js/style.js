jQuery(document).ready(function($) {

    //**************************************//
    //******** SELECT PRODUCT BUMP ********//
    //************************************//
    function formatState (state) {
        if (!state.id) {
            return state.text;
        }
        console.log(state);
        var image = state.element.attributes[1].value;
        var $state = $(
            '<span><img src="' + image + '" width="16px" height="16px"/> ' + state.text + '</span>'
        );
        return $state;
    };
    $(".product-bump").select2({
        templateResult: formatState,
    });

    //**************************************//
    //******** SELECT PRODUCT BUMP ********//
    //************************************//
});