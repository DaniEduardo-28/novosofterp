<template>
  <section class="card card-dashboard">
    <div class="card-body" v-if="loader">
      <template>
        <vcl-table :rows="4" :columns="2"></vcl-table>
      </template>
    </div>
    <div class="card-body pb-0" v-show="!loader">
      <div class="row">
        <label
          >Productos por vencer
          <el-tooltip
            class="item"
            effect="dark"
            content="Aplica filtro por establecimiento"
            placement="top-start"
          >
            <i class="fa fa-info-circle"></i>
          </el-tooltip>
        </label>

        <!-- <div class="col-md-3">
          <label class="control-label">Fecha inicio</label>
          <el-date-picker
            v-model="date_start"
            type="date"
            @change="changeDisabledDates"
            value-format="yyyy-MM-dd"
            format="dd/MM/yyyy"
            :clearable="true"
          ></el-date-picker>
        </div>
        <div class="col-md-3">
          <label class="control-label">Fecha término</label>
          <el-date-picker
            v-model="date_end"
            type="date"
            :picker-options="pickerOptionsDates"
            @change="getRecords"
            value-format="yyyy-MM-dd"
            format="dd/MM/yyyy"
            :clearable="true"
          ></el-date-picker>
        </div> -->
      </div>
    </div>

    <div class="card-body p-0" v-show="!loader">
      <inventory-data-table :resource="resource">
        <tr slot="heading">
          <th>#</th>
          <th>Producto</th>
          <th class="text-center">Stock</th>
          <th>Lote</th>
          <th>Precio</th>
          <th>F. Vencim</th>
          <th>Almacén</th>
        </tr>
        <tr slot-scope="{ index, row }">
          <td>{{ index }}</td>
          <td>{{ row.product }}</td>
          <td class="text-center">{{ row.stock }}</td>
          <td>{{ row.lot }}</td>
          <td>{{ row.price }}</td>
          <td>{{ row.date }}</td>
          <td>{{ row.warehouse }}</td>
        </tr>
      </inventory-data-table>
    </div>
  </section>
</template>


<script>
import InventoryDataTable from "../../components/InventoryDataTable.vue";
import { VclTable } from "vue-content-loading";

export default {
  components: { InventoryDataTable, VclTable },

  data() {
    return {
      loader: true,
      resource: "dashboard/product-of-due",
      records: [],
      date_start: moment().startOf("month").format("YYYY-MM-DD"),
      date_end: moment().endOf("month").format("YYYY-MM-DD"),
    };
  },
  mounted() {
    const currentDate = new Date();
    const threeMonthsLater = new Date();
    currentDate.setMonth(currentDate.getMonth() - 6);
    threeMonthsLater.setMonth(currentDate.getMonth() + 6);
    this.date_start = currentDate.toISOString().split("T")[0];
    this.date_end = threeMonthsLater.toISOString().split("T")[0];
    this.events();
  },
  created() {},
  methods: {
    events() {
      this.$eventHub.$on("recordsSkeletonLoader", (status) => {
        this.loader = status;
      });

      this.$eventHub.$on("changeStock", (establishment_id) => {
        this.$eventHub.$emit("reloadSimpleDataTable", establishment_id);
      });
      this.$eventHub.$emit("reloadSimpleDate", this.date_start, this.date_end);
    },
  },
};
</script>
