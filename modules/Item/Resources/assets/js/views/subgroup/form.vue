<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{ 'has-danger': errors.name }">
                            <label class="control-label">Nombre del Grupo</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{ 'has-danger': errors.category_id }">
                            <label class="control-label">Categoría</label>
                            <el-select v-model="form.category_id" placeholder="Seleccione una categoría">
                                <el-option v-for="category in categories" :key="category.id" :value="category.id"
                                    :label="category.name">
                                </el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.category_id"
                                v-text="errors.category_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-comtrol">
                            <el-checkbox v-model="form.status">
                                Estado
                            </el-checkbox>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ['showDialog', 'recordId'],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            resource: 'subgroups',
            form: {},
            errors: {},
            categories: []
        };
    },
    created() {
        this.initForm();
        this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.categories = response.data.categories
            })
    },
    methods: {
        initForm() {
            this.loading_submit = false;
            this.errors = {};
            this.form = {
                id: null,
                name: null,
                category_id: null,
                status: true,
            };
        },
        resetForm() {
            this.initForm();
        },
        create() {
            this.titleDialog = this.recordId ? 'Editar Subgrupo' : 'Nuevo Subgrupo';
            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data;
                    })
            }
        },
        submit() {
            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataItems', response.data.id)
                        } else {
                            this.$eventHub.$emit('reloadData')
                        }
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message)
                        console.log(error)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.resetForm()
        },
    }
};
</script>
