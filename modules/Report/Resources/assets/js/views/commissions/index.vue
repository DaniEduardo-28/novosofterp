<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-info">
      <h3 class="my-0">Comisiones vendedores - ventas</h3>
    </div>
    <div class="card mb-0">
      <div class="card-body">
        <data-table :applyCustomer="true" :resource="resource">
          <tr slot="heading">
            <th>#</th>
            <th>Vendedor</th>
            <th class="text-center">TRANSACCIONES</th>
            <th class="text-center">VALOR DE VENTA</th>
            <th class="text-center">IGV</th>
            <th class="text-center">TOTAL DE VENTA</th>
          </tr>
          <tr v-if="Number(row.acum_sales.replace(/,/g, '')) > 0" slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.user_name }}</td>
            <td class="text-center">{{ row.total_transactions }}</td>
            <td class="text-center">
                {{ (Number(row.acum_sales.replace(/,/g, '')) / 1.18).toFixed(2) }}
            </td>
            <td class="text-center">
                {{ (Number(row.acum_sales.replace(/,/g, '')) - (Number(row.acum_sales.replace(/,/g, '')) / 1.18)).toFixed(2) }}
            </td>
            <td class="text-center">{{ row.acum_sales }}</td>
          </tr>
        </data-table>
      </div>
    </div>
  </div>
</template>

<script>
import DataTable from "../../components/DataTableCommissions.vue";

export default {
  components: { DataTable },
  data() {
    return {
      resource: "reports/commissions",
      form: {},
    };
  },
  async created() {},
  methods: {},
};
</script>
