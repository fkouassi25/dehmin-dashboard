function mydataTable() {
  $('.datatable').DataTable({
    "language": {
      "search": "Recherche:",
      "lengthMenu": "Affiche _MENU_ lignes",
      "zeroRecords": "Aucun élément à afficher",
      "info": "Page _PAGE_ sur _PAGES_",
      "infoEmpty": "",
      "infoFiltered": "(filtered from _MAX_ total records)",
      "paginate": {
        "previous": "Précédent",
        "next": "Suivant"
      }
    }
  });
}