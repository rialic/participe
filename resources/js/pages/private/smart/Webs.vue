<template>
    <base-smart-view
        page-title="Tele-educação"
        store-title="Lista de atividades de tele-educação"
        :headers="headers"
        :default-sort-by="'tb_events.name'"
        filter-key="scope_search_webs"
        :form-config="formConfig"
        :tag-validation="tagValidation"
        :sort-by-mapping="sortByMapping"
        load-function="indexWebs"
        send-function="sendWebs"
    >
        <template #table-row="{ item }">
            <tr>
                <td>{{ item.event }}</td>
                <td>{{ item.started_at }}</td>
                <td>{{ item.bireme_code }}</td>
                <td>{{ item.organization }}</td>
                <td>{{ item.participant }}</td>
                <td>{{ item.signed_up_at }}</td>
                <td>{{ item.cpf }}</td>
                <td>{{ item.cnes }}</td>
                <td>{{ item.cbo }}</td>
                <td>{{ item.state }}</td>
                <td>{{ item.city }}</td>
            </tr>
        </template>

        <template #custom-form="{ form }">
            <v-row>
                <v-col col="12" md="4" class="pt-4 pb-1">
                    <v-text-field
                        label="Data *"
                        v-model="form.custom_date"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        hint="Informe o perído que deseja enviar"
                        :persistent-hint="true"
                        placeholder="Data"
                        autocomplete="false"
                        v-maska:[cpf]="'##/####'"
                        clearable>
                    </v-text-field>
                </v-col>
            </v-row>
        </template>
    </base-smart-view>
</template>

<script setup>
import BaseSmartView from '@/pages/private/smart/BaseSmartView.vue'

const headers = [
    { title: 'Evento', icon: 'fa-sort' },
    { title: 'Data do evento', icon: 'fa-sort' },
    { title: 'Bireme' },
    { title: 'Organização' },
    { title: 'Participante', icon: 'fa-sort' },
    { title: 'Data participação', icon: 'fa-sort' },
    { title: 'Cpf' },
    { title: 'Cnes' },
    { title: 'Cbo' },
    { title: 'Estado' },
    { title: 'Cidade' },
]

const formConfig = {
    custom_date: null,
}

const sortByMapping = {
    'Evento': 'tb_events.name',
    'Data do evento': 'tb_events.start_at',
    'Participante': 'tb_users.name',
    'Data participação': 'tb_event_participants.created_at'
}
</script>