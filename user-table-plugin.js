jQuery(document).ready(function($) {
    $('#user-table').DataTable({
        searching: true, // Enable searching
        ordering: false, // Disable sorting
        dom: 'ftip',
        pageLength: 20,
        order: [[ 1, "asc" ]],
    });
});
