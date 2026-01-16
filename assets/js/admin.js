// admin.js - CarePlan Admin JS

jQuery(document).ready(function ($) {

    // Confirm delete action globally
    $('.careplan-delete').on('click', function (e) {
        if (!confirm(careplanAdmin.confirmDelete || 'Are you sure you want to delete this record?')) {
            e.preventDefault();
        }
    });

    // Example: highlight rows on hover
    $('.careplan-table tbody tr').hover(
        function () {
            $(this).css('background-color', '#ffffe0');
        },
        function () {
            $(this).css('background-color', '');
        }
    );

});
