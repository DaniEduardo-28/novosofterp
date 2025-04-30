<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form @submit.prevent="submit">
            <div class="row mt-3">
                <!-- Datos del Paciente -->
                <div class="col-md-6">
                    <div class="form-group" :class="{ 'has-danger': errors.identity_document_type_id }">
                        <label class="control-label">Tipo de Doc. Identidad<span class="text-danger">*</span></label>
                        <el-select v-model="form.identity_document_type_id" filterable>
                            <el-option v-for="option in identity_document_types" :key="option.id"
                                :label="option.description" :value="option.id"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.identity_document_type_id"
                            v-text="errors.identity_document_type_id[0]"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div :class="{ 'has-danger': errors.number }" class="form-group">
                        <label class="control-label">Número</label>
                        <el-input v-model="form.number" @input="form.number = form.number.toUpperCase()" :maxlength="maxLength">
                            <template
                                v-if="form.identity_document_type_id == '6' || form.identity_document_type_id == '1'">
                            </template>
                        </el-input>
                        <small v-if="errors.number" class="form-control-feedback" v-text="errors.number[0]"></small>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group" :class="{ 'has-danger': errors.name }">
                        <label>Nombres</label>
                        <el-input v-model="form.name" @input="form.name = form.name.toUpperCase()"></el-input>
                        <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{ 'has-danger': errors.last_name }">
                        <label>Apellidos</label>
                        <el-input v-model="form.last_name"
                            @input="form.last_name = form.last_name.toUpperCase()"></el-input>
                        <small class="form-control-feedback" v-if="errors.last_name"
                            v-text="errors.last_name[0]"></small>
                    </div>
                </div>
            </div>
            <!-- Dirección -->
            <div class="row mt-3">
                <div class="col-md-9">
                    <div :class="{ 'has-danger': errors.ubigeo }" class="form-group">
                        <label class="control-label">Ubigeo</label>
                        <el-cascader v-model="form.ubigeo" :clearable="true" :options="locations"
                            filterable></el-cascader>
                        <small v-if="errors.ubigeo" class="form-control-feedback" v-text="errors.ubigeo[0]"></small>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Dirección</label>
                        <el-input v-model="form.address" @input="form.address = form.address.toUpperCase()"></el-input>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <el-input v-model="form.phone" @input="validateNumber('phone')" maxlength="15"></el-input>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <el-input v-model="form.email"></el-input>
                    </div>
                </div>
            </div>

            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

import { mapActions, mapState } from "vuex/dist/vuex.mjs";
import { serviceNumber } from '../../../../../../../resources/js/mixins/functions.js';

export default {
    mixins: [serviceNumber],
    props: ["showDialog", "recordId", "document_type_id"],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            resource: "patient",
            form: {},
            errors: {},
            identity_document_types: [],
            loading_search: false,
            locations: [],
        };
    },
    created() {
        this.initForm();
        this.$http.get(`/${this.resource}/tables`).then(response => {
            console.log(response.data);
            this.api_service_token = response.data.api_service_token
            this.identity_document_types = response.data.identity_document_types;
            this.locations = response.data.locations;
            if (!this.recordId) {
                const dniOption = this.identity_document_types.find(option => option.description === 'DNI');
                if (dniOption) {
                    this.form.identity_document_type_id = dniOption.id;
                }
            }
        }).catch(() => {
        });
    },
    methods: {
        searchCustomer() {
            this.form.identity_document_type_id = 1;
            this.searchServiceNumber()
        },
        validateNumber(field) {
            const value = this.form[field];
            if (!/^\d+$/.test(value)) {
                this.form[field] = value.replace(/\D/g, ""); // Solo números
            }
        },
        onlyNumbers(event) {
            const key = event.key;
            if (!/^\d$/.test(key)) {
                event.preventDefault();
            }
        },
        initForm() {
            this.form = {
                id: null,
                identity_document_type_id: null,
                number: null,
                name: null,
                last_name: null,
                address: null,
                ubigeo: [],
                phone: null,
                email: null,
            };
            this.errors = {};
            this.loading_submit = false;
        },
        create() {
            this.titleDialog = this.recordId ? "Editar Paciente" : "Nuevo Paciente";

            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`).then((response) => {
                    const data = response.data.data;

                    // Asignar los datos del paciente al formulario
                    this.form = { ...data };

                    // Mantener el tipo de documento y validarlo con los documentTypes
                    const identity_document_type = this.identity_document_types.find(
                        type => type.description === data.identity_document_type
                    );
                    if (identity_document_type) {
                        this.form.identity_document_type_id = identity_document_type.id;
                    }
                });
            } else {
                this.initForm(); // Resetea el formulario para nuevos registros
            }
        },

        validateDigits() {

            const pattern_number = new RegExp('^[0-9]+$', 'i');

            if (this.form.identity_document_type_id == '6') {

                if (this.form.number.length !== 11) {
                    return {
                        success: false,
                        message: `El campo solo debe tener 11 dígitos.`
                    }
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo solo debe contener números`
                    }
                }

            }


            if (this.form.identity_document_type_id == '1') {

                if (this.form.number.length !== 8) {
                    return {
                        success: false,
                        message: `El campo solo debe tener 8 dígitos.`
                    }
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo debe contener solo números`
                    }
                }
            }


            if (['4', '7', '0'].includes(this.form.identity_document_type_id)) {

                const pattern = new RegExp('^[A-Z0-9\-]+$', 'i');

                if (!pattern.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo no cumple con el formato establecido`
                    }
                }

            }


            return {
                success: true
            }
        },
        submit() {
            this.loading_submit = true;
            this.form.name = this.form.name ? this.form.name.toUpperCase() : '';
            this.form.last_name = this.form.last_name ? this.form.last_name.toUpperCase() : '';
            this.form.address = this.form.address ? this.form.address.toUpperCase() : '';
            this.form.number = this.form.number ? this.form.number.toUpperCase() : '';
            console.log(this.form)
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    console.log(error)
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        setDataDefaultCustomer() {

            if (this.form.identity_document_type_id == '0') {
                this.form.number = '99999999'
                this.form.name = "Pacientes - Varios"
            } else {
                this.form.number = ''
                this.form.name = null
            }

        },
        close() {
            this.$eventHub.$emit('initInputPerson')
            this.$emit("update:showDialog", false);
            this.initForm();
        },

    },
    computed: {
        ...mapState([
            'config',
            'person',
            'parentPerson',
        ]),
        maxLength() {
            if (this.form.identity_document_type_id == '6') return 11; // RUC
            if (this.form.identity_document_type_id == '1') return 8;  // DNI
            return 20; // Longitud máxima predeterminada
        }
    }
};
</script>