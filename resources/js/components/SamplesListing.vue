<template>
  <div class="sample-listing">
    <div class="row">
      <div class="col-sm-8">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input v-model="filter" class="form-control" :placeholder="'Search Request...'">
          </div>
        </div>
        <div class="col-sm-4">
            <b-btn v-b-modal.sample-modal class="float-right btn-action"><i class="fa fa-user-friends"></i>Reassign Request(s)</b-btn>
        </div>
        <b-modal id="sample-modal"
                  size="md" centered :title="'Reassign to'"
                  ref="modal"
                  header-close-content="&times;"
                  v-model="showReassignment"
                  v-cloak>
              <div class="form-group">
                  <select v-model="selectedUser" class="form-control">
                      <option v-for="user in users" v-bind:value="user.id">
                          {{user.fullname}}
                      </option>
                  </select>
              </div>
              <div slot="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" @click="clearForm">Cancel</button>
                  <button type="button" class="btn btn-secondary ml-2" @click="onSubmit" :disabled="disabled">Reassign</button>
              </div>
          </b-modal>
    </div>
    <div class="row">
      <div class="col-sm-12"><br /></div>
    </div>
    <div class="data-table"></div>
      <data-loading
              :for=/requests\?page|results\?page/
              v-show="shouldShowLoader"
              :empty="'No Data Available'"
              :empty-desc="''"
              empty-icon="noData"
      />
      <div v-show="!shouldShowLoader"  class="card card-body">
        <vuetable
          :dataManager="dataManager"
          :sortOrder="sortOrder"
          :css="css"
          :api-mode="false"
          @vuetable:pagination-data="onPaginationData"
          @vuetable:row-clicked="usersChecked"
          :fields="fields"
          :data="data"
          data-path="data"
          pagination-path="meta"
          ref="vuetable"
        >
          <template slot="ids" slot-scope="props">
            <b-link class="text-nowrap" :href="openRequest(props.rowData, props.rowIndex)">#{{props.rowData.id}}</b-link>
          </template>
          <template slot="name" slot-scope="props">
            <span v-uni-id="props.rowData.id.toString()">{{ props.rowData.name }}</span>
          </template>
          <template slot="participants" slot-scope="props">
            <span
              v-for="participant in props.rowData.participants.slice(-1)"
            >{{ participant.fullname }}</span>
          </template>
        </vuetable>
        <pagination
          :single="'Request'"
          :plural="'Requests'"
          :perPageSelectEnabled="true"
          @changePerPage="changePerPage"
          @vuetable-pagination:change-page="onPageChange"
          ref="pagination"
        ></pagination>
      </div>
  </div>
</template>

<script>
import Vue from "vue";
import datatableMixin from "./common/mixins/datatable";
import dataLoadingMixin from "./common/mixins/apiDataLoading.js";
import Pagination from "./common/Pagination";

import { createUniqIdsMixin } from "vue-uniq-ids";
const uniqIdsMixin = createUniqIdsMixin();

export default {
  mixins: [datatableMixin, dataLoadingMixin, uniqIdsMixin],
  props: {
    filter: {},
    columns: {},
    pmql: {},
    savedSearch: {
      default: false
    }
  },
  data() {
    return {
      orderBy: "id",
      orderDirection: "DESC",
      additionalParams: "",
      sortOrder: [
        {
          field: "id",
          sortField: "id",
          direction: "desc"
        }
      ],
      fields: [{
          name: '__checkbox',
          titleClass: 'center aligned',
          dataClass: 'center aligned'
      }],
      previousFilter: "",
      previousPmql: "",
      selectedRequests: [],
      showReassignment: false,
      users: [],
      selectedUser: []
    };
  },
  computed: {
    endpoint() {
      if (this.savedSearch !== false) {
        return `saved-searches/${this.savedSearch}/results`;
      }

      return 'requests';
    },
    disabled () {
      return this.selectedUser ? this.selectedUser.length === 0 : true;
    }
  },
  mounted() {
    this.setupColumns();
  },
  methods: {
    setupColumns() {
      let columns = this.getColumns();
      columns.forEach(column => {
        let field = {
          title: () => column.label
        };

        switch (column.field) {
          case 'id':
            field.name = '__slot:ids';
            field.title = '#';
            break;
          case 'name':
            field.name = '__slot:name';
            break
          case 'participants':
            field.name = '__slot:participants';
            break;
          default:
            field.name = column.field;
        }

        if (!field.field) {
          field.field = column.field;
        }

        if (column.sortable === true && !field.sortField) {
          field.sortField = column.field;
        }

        this.fields.push(field);
      });

      // this is needed because fields in vuetable2 are not reactive
      this.$nextTick(()=>{
        this.$refs.vuetable.normalizeFields();
      });
    },
    getColumns() {
      if (this.$props.columns) {
        return this.$props.columns;
      } else {
        return [
          {
            "label": "#",
            "field": "id",
            "sortable": true,
            "default": true
          },
          {
            "label": "Name",
            "field": "name",
            "sortable": true,
            "default": true
          },
          {
            "label": "Status",
            "field": "status",
            "sortable": true,
            "default": true
          },
          {
            "label": "Assignee",
            "field": "participants",
            "sortable": false,
            "default": true
          }
        ];
      }
    },
    openRequest(data, index) {
      return "/requests/" + data.id;
    },
    formatStatus(status) {
      let color = "success",
        label = "In Progress";
      switch (status) {
        case "DRAFT":
          color = "danger";
          label = "Draft";
          break;
        case "CANCELED":
          color = "danger";
          label = "Canceled";
          break;
        case "COMPLETED":
          color = "primary";
          label = "Completed";
          break;
        case "ERROR":
          color = "danger";
          label = "Error";
          break;
      }
      return (
        '<i class="fas fa-circle text-' +
        color +
        '"></i> <span>' +
          label +
        "</span>"
      );
    },
    transform(data) {
      for (let i = 0; i < data.data.length; i += 1) {
        if (typeof (data.data[i]) === 'object') {
          try {
            data.data[i] = JSON.parse(JSON.stringify(data.data[i]));
          } catch (e) {
            console.error(e);
          }
        }
      }
      return data;
    },
    usersList() {
      ProcessMaker.apiClient
        .get('users'
        )
        .then(response => {
          this.users = response.data.data;
        }).catch(error => {
          if (_.has(error, 'response.data.message')) {
            ProcessMaker.alert(error.response.data.message, 'danger');
          } else if(_.has(error, 'response.data.error')) {
            return;
          }  else {
            throw error;
          }
        });
    },
    onSubmit (evt) {
      evt.preventDefault();
      this.submitted = true;
          if (this.selectedUser) {
            console.log(this.selectedUser);
            this.disabled = true;

            for( var i = 0; i < this.selectedRequests.length; i++ ) {
              ProcessMaker.apiClient
                .put("tasks/" + this.selectedRequests[i] * 2, {
                  user_id: this.selectedUser
              })
              .then(response => {
                this.showReassignment = false;
                this.selectedUser = [];
                this.redirect('package-batch-reassignment');
                //this.fetch();
                //this.showReassignment = false;
              }).catch(error => {
                if (_.has(error, 'response.data.message')) {
                  ProcessMaker.alert(error.response.data.message, 'danger');
                } else if(_.has(error, 'response.data.error')) {
                  return;
                }  else {
                  throw error;
                }
              });
            }


      }
    },
    redirect(to, forceRedirect = false) {
      this.redirectInProcess = true;
      window.location.href = to;
    },
    clearForm () {
        this.showReassignment = false;
        this.selectedUser = [];
    },
    usersChecked(data, field) {
      if(field.srcElement.checked === false) {
        for( var i = 0; i < this.selectedRequests.length; i++ ) {
          if ( this.selectedRequests[i] === data.id) { 
            this.selectedRequests.splice(i, 1); 
          }
        }
      }else if (field.srcElement.checked === true){
        this.selectedRequests.push(data.id);
      }

      console.log(this.selectedRequests);
    },
    fetch() {
        Vue.nextTick(() => {
            let pmql = '';

            if (this.pmql !== undefined) {
                pmql = this.pmql;
            }

            let filter = this.filter;

            if (filter && filter.length) {
              if (filter.isPMQL()) {
                pmql = `(${pmql}) and (${filter})`;
                filter = '';
              }
            }

            if (this.previousFilter !== filter) {
              this.page = 1;
            }

            this.previousFilter = filter;

            if (this.previousPmql !== pmql) {
              this.page = 1;
            }

            this.previousPmql = pmql;

            // Load from our api client
            ProcessMaker.apiClient
              .get(
                `${this.endpoint}?page=` +
                  this.page +
                  "&per_page=" +
                  this.perPage +
                  "&include=process,participants,data" +
                  "&pmql=" +
                  encodeURIComponent(pmql) +
                  "&filter=" +
                  filter +
                  "&order_by=" +
                  (this.orderBy === "__slot:ids" ? "id" : this.orderBy) +
                  "&order_direction=" +
                  this.orderDirection +
                  this.additionalParams
              )
              .then(response => {
                this.data = this.transform(response.data);
                document.getElementsByClassName('vuetable-th-checkbox-id')[0].style.visibility = 'hidden';
              }).catch(error => {
                if (_.has(error, 'response.data.message')) {
                  ProcessMaker.alert(error.response.data.message, 'danger');
                } else if(_.has(error, 'response.data.error')) {
                  return;
                }  else {
                  throw error;
                }
              });

        });
    }
  },
  beforeMount() {
    this.usersList();
  }
};
</script>

<style lang="scss" scoped>
</style>
