$(document).ready(function () {
    $('#sidebarCollapse').on("click", function () {
        $("#sidebar").toggleClass("active");
        $(".caksidemenu span").toggleClass("d-none");
    });
});