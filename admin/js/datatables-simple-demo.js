window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    // const datatablesSimple = document.getElementById('datatablesSimple');
    const dataTables = document.getElementsByClassName('data-table');
    for (i = 0; i < dataTables.length; i++) {
        if (dataTables[i]) {
            // dataTables[i].DataTable();
            // new simpleDatatables.DataTable(dataTables[i]);
        }
    }
    // document.getElementsByClassName('data-table').DataTable();
});
