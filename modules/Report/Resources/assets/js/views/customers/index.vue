<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-info">
      <h3 class="my-0">Consulta de documentos por cliente</h3>
    </div>
    <div class="card mb-0">
      <div class="card-body">
        <data-table :resource="resource">
          <tr slot="heading">
            <th class="">#</th>
            <th class="">F. Emsión</th>
            <th class="">F. Vencimiento</th>
            <th class="">Tipo Documento</th>
            <th class="">Serie</th>
            <th class="">Número</th>
            <th class="">Moneda</th>
            <th class="">Total</th>
            <th class="">Saldo</th>
            <th class="">Productos</th>
            <th class="">Pagos</th>
          </tr>

          <tr></tr>
          <tr slot-scope="{ index, row }" v-if="row.notes != null && row.notes.length == 0">
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ index }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ row.date_of_issue }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{
                row.date_of_due
                ? row.date_of_due
                : "No tiene fecha de vencimiento."
              }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ row.document_type_description }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ row.series }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ row.alone_number }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{ row.currency_type_id }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{
                row.document_type_id == "07"
                ? row.total == 0
                  ? "0.00"
                  : "-" + row.total
                : row.document_type_id != "07" &&
                  (row.state_type_id == "11" || row.state_type_id == "09")
                  ? "0.00"
                  : row.total
              }}
            </td>
            <td :class="
              row.total_payment >= row.total
                ? 'text-success'
                : isDateWarning(row.date_of_due)
                  ? 'text-danger'
                  : ''
            ">
              {{
                row.document_type_id == "07"
                ? row.total == 0
                  ? "0.00"
                  : "-" + (row.total - row.total_payment)
                : row.document_type_id != "07" &&
                  (row.state_type_id == "11" || row.state_type_id == "09")
                  ? "0.00"
                  : row.total - row.total_payment
              }}
            </td>
            <td class="text-center">
              <button class="btn waves-effect waves-light btn-xs btn-primary" type="button"
                @click.prevent="clickViewProducts(row.items)">
                <i class="fa fa-eye"></i>
              </button>
            </td>
            <td>
              <template>
                <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                  @click.prevent="clickDocumentPayment(row.id)">
                  Pagos
                </button>
              </template>
            </td>
          </tr>
        </data-table>
      </div>
    </div>

    <document-payments :showDialog.sync="showDialogDocumentPayments" :documentId="recordId" :external="true"
      :configuration="this.configuration"></document-payments>

      <product-sale :records="recordsItems" :showDialog.sync="showDialogProducts">

</product-sale>
  </div>
</template>

<script>
import DataTable from "../../components/DataTableCustomers.vue";
import DocumentPayments from "@views/documents/partials/payments.vue";
import ProductSale from './partials/product_sale.vue';

export default {
  components: { DocumentPayments, DataTable, ProductSale },
  data() {
    return {
      resource: "reports/customers",
      showDialogDocumentPayments: false,
      recordId: null,
      form: {},
      showDialogProducts: false,
      recordsItems: [],
    };
  },
  async created() { },
  methods: {
    isDateWarning(date_due) {
      let today = Date.now();
      return moment(date_due).isBefore(today);
    },
    clickViewProducts(items = []) {
      this.recordsItems = items;
      this.showDialogProducts = true;
    },
    clickDocumentPayment(recordId) {
      this.recordId = recordId;
      this.showDialogDocumentPayments = true;
    },
  },
};
</script>
