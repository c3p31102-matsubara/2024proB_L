import { insert, dataType } from './APIaccessor.js';

$(document).ready(function() {
    $('#submitBtn').click(async function() {
        const formData = {
            lastName: $('#last-name').val(),
            firstName: $('#first-name').val(),
            email: $('#email').val(),
            itemCategory: $('#item-category').val(),
            location: $('input[name="location"]:checked').map(function() {
                return this.value;
            }).get(),
            color: $('#color').val(),
            features: $('#features').val()
        };

        const result = await insert(dataType.lostitemlist, formData);
        console.log(result);
    });
});
