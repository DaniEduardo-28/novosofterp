<template>
  <div>
    <div class="page-header pr-0">
      <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
      <ol class="breadcrumbs">
        <li class="active"><span>{{ title }}</span></li>
      </ol>
      <div class="right-wrapper pull-right">
        <button type="button" class="btn btn-custom btn-sm mt-2 mr-2" @click.prevent="clickCreate()">
          <i class="fa fa-plus-circle"></i> Nuevo
        </button>
      </div>
    </div>

    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">Listado de {{ title }}</h3>
      </div>
      <div class="card-body">
        <data-table :resource="resource">
          <tr slot="heading">
            <th>#</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Tipo de Documento</th>
            <th>N° Documento</th>
            <th class="text-right">Acciones</th>
          </tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.name }}</td>
            <td>{{ row.last_name }}</td>
            <td>{{ row.identity_document_type }}</td>
            <td>{{ row.number }}</td>
            <td class="text-right">
              <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                @click.prevent="clickCreate(row.id)">Editar</button>
              <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                @click.prevent="clickDelete(row)">Eliminar</button>
            </td>
          </tr>
        </data-table>
      </div>

      <patient-form :showDialog.sync="showDialog" :recordId="recordId"></patient-form>
    </div>
  </div>
</template>

<script>
import PatientForm from './form.vue'
import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
import { deletable } from '../../../../../../../resources/js/mixins/deletable'

export default {
  mixins: [deletable],
  components: { DataTable, PatientForm },
  data() {
    return {
      title: null,
      showDialog: false,
      resource: "patient",
      recordId: null,
    }
  },
  created() {
    this.title = 'Pacientes'
  },
  methods: {
    clickCreate(recordId = null) {
      this.recordId = recordId
      this.showDialog = true
    },
    clickDelete(row) {
      const fullName = `${row.name} ${row.last_name || ''}`.trim()

      this.$confirm(`¿Seguro de eliminar a ${fullName}?`, 'Confirmación', {
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        type: 'warning'
      }).then(() => {
        this.destroy(`/${this.resource}/${row.id}`).then(() =>
          this.$eventHub.$emit('reloadData')
        )
      }).catch(() => {
        // Cancelado
      })
    }
  }
}
</script>