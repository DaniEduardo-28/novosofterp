<template>
  <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" append-to-body>
    <form autocomplete="off" @submit.prevent="clickAddItem">
      <div class="form-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group" :class="{ 'has-danger': errors.individual_item_id }">
              <label class="control-label"> Productos </label>
              <el-select v-model="form.individual_item_id" @change="changeItem" filterable placeholder="Buscar">
                <el-option v-for="option in individual_items" :key="option.id" :value="option.id"
                  :label="option.full_description"></el-option>
              </el-select>

              <small class="form-control-feedback" v-if="errors.individual_item_id"
                v-text="errors.individual_item_id[0]"></small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" :class="{ 'has-danger': errors.quantity }">
              <label class="control-label">Cantidad</label>
              <el-input-number v-model="form.quantity" :min="0.01"></el-input-number>
              <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" :class="{ 'has-danger': errors.stock }">
              <br>
              <label class="control-label">Stock</label>
              <label>{{ form.stock }} {{ form.unit_type_text }}</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <br>
              <label class="control-label">Precio</label>
              <label>{{ form.currency_type_symbol }} {{ form.sale_unit_price }}</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="table-responsive">
            <table class="table table-sm mb-0 table-borderless">
              <thead>
                <tr>
                  <th>Sucursal</th>
                  <th>Stock</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in form.warehouses" :key="index">
                  <td>{{ row.warehouse_description }}</td>
                  <td>{{ row.stock }} {{ form.unit_type_text }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="form.item_unit_types && form.item_unit_types.length > 0" class="table-responsive mt-3">
          <h5 class="separator-title">
            Lista de Precios
            <el-tooltip class="item" content="Selecciona el precio deseado para que se aplique como precio unitario"
              effect="dark" placement="top">
              <i class="fa fa-info-circle"></i>
            </el-tooltip>
          </h5>
          <table class="table table-sm table-bordered">
            <thead class="bg-light">
              <tr>
                <th class="text-center">Unidad</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Factor</th>
                <th class="text-center">Precio 1</th>
                <th class="text-center">Precio 2</th>
                <th class="text-center">Precio 3</th>
                <th class="text-center">Precio Default</th>
                <th class="text-center">Seleccionar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in form.item_unit_types" :key="index">
                <td class="text-center">{{ row.unit_type_id }}</td>
                <td class="text-center">{{ row.description }}</td>
                <td class="text-center">{{ row.quantity_unit }}</td>
                <td class="text-center">{{ row.price1 }}</td>
                <td class="text-center">{{ row.price2 }}</td>
                <td class="text-center">{{ row.price3 }}</td>
                <td class="text-center">Precio {{ row.price_default }}</td>
                <td class="text-center">
                  <el-button size="mini" :type="selectedPriceIndex === index ? 'success' : 'primary'"
                    icon="el-icon-check" @click="selectPrice(row, index)"></el-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <div class="form-actions text-right mt-2">
        <el-button @click.prevent="close()">Cerrar</el-button>
        <el-button type="primary" native-type="submit" v-if="form.individual_item_id">Agregar</el-button>
      </div>
    </form>
  </el-dialog>
</template>
<style>
.el-select-dropdown {
  max-width: 80% !important;
  margin-right: 5% !important;
}
</style>
<script>
export default {
  props: ["showDialog"],
  data() {
    return {
      titleDialog: "Agregar Producto",
      resource: "item-sets",
      errors: {},
      form: {},
      individual_items: [],
      selectedPriceIndex: null,
    };
  },
  created() {
    this.initForm();

    this.$http.get(`/${this.resource}/item/tables`).then((response) => {
      this.individual_items = response.data.individual_items;
    });
  },
  methods: {
    initForm() {
      this.errors = {};

      this.form = {
        individual_item_id: null,
        sale_unit_price: 0,
        purchase_unit_price: 0,
        quantity: 1,
        full_description: null,
        stock: 0,
        warehouses: [],
        currency_type_id: null,
        currency_type_symbol: null,
        unit_type_text: null,
      };
    },
    create() { },
    close() {
      this.initForm();
      this.$emit("update:showDialog", false);
    },
    changeItem() {
      let item = _.find(this.individual_items, {
        id: this.form.individual_item_id,
      });
      this.form.sale_unit_price = item.sale_unit_price;
      this.form.full_description = item.full_description;
      this.form.purchase_unit_price = item.purchase_unit_price;
      this.form.stock = item.stock;
      this.form.quantity = 1;
      this.form.sale_unit_price = item.sale_unit_price;
      this.form.warehouses = item.warehouses;
      this.form.currency_type_id = item.currency_type_id;
      this.form.currency_type_symbol = item.currency_type_symbol;
      this.form.description = item.description;
      this.form.brand = item.brand;
      this.form.laboratory = item.laboratory;
      this.form.item_unit_types = item.item_unit_types || [];
      this.form.unit_type_text = item.unit_type_text;
    },
    selectPrice(row, index) {
      this.selectedPriceIndex = index;

      let valor = 0;
      switch (row.price_default) {
        case 1:
          valor = row.price1;
          break;
        case 2:
          valor = row.price2;
          break;
        case 3:
          valor = row.price3;
          break;
      }

      this.form.sale_unit_price = valor;
      this.form.unit_type_text = row.unit_type_id;
    },
    async clickAddItem() {
      this.$emit("add", this.form);
      this.initForm();
    },
  },
};
</script>
