$(document).ready(function()
{
    $('.more_operations').click(function(event)
    {
        alert($(this).html);
        event.preventDefault();
            $('#loader').show();
            $('#jobs').load(
                $(this).parent('form').attr('action'),
                { query: this.value ? this.value + '*' : this.value },
                function() {
                    $('#loader').hide();
                }
            );
        
    });
});


