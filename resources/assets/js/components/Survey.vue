<template>
  <div>
    <div v-if="view=='list'">
      <div class="card">
        <div class="card-header">
          Surveys
          <button @click="surveyForm" class="btn btn-primary float-right">Create Survey</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered" id="surveys-table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="survey in surveys">
                <td>{{ survey.id }}</td>
                <td>{{ survey.name }}</td>
                <td>
                  <button @click="editForm(survey)" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <button @click="showUsers(survey.id)" class="btn btn-info">
                    <i class="fa fa-users"></i> Users
                  </button>
                  <button @click="deleteSurvey(survey.id)" class="btn btn-danger">
                    <i class="fa fa-remove"></i> Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else-if="view=='form'">
      <div class="card">
        <div class="card-header">New Survey</div>
        <div class="card-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="newForm.name">
          </div>

          <div class="form-group">
            <label>Users</label>
            <v-select multiple @search="onSearch" v-model="newForm.users" :options="users">
              <template slot="no-options">type to search users</template>
              <template slot="option" slot-scope="option">
                <div class="d-center">{{ option.name }}</div>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">{{ option.name }}</div>
              </template>
            </v-select>
          </div>

          <button @click="createSurvey" class="btn btn-primary float-right">Create Survey</button>
        </div>
      </div>
    </div>

    <div v-else-if="view=='edit'">
      <div class="card">
        <div class="card-header">{{ currentSurvey.name }}</div>
        <div class="card-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="currentSurvey.name">
          </div>

          <div class="form-group">
            <label>Users</label>
            <v-select multiple @search="onSearch" v-model="currentSurvey.users" :options="users">
              <template slot="no-options">type to search users</template>
              <template slot="option" slot-scope="option">
                <div class="d-center">{{ option.name }}</div>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">{{ option.name }}</div>
              </template>
            </v-select>
          </div>

          <button @click="updateSurvey" class="btn btn-primary float-right">Update Survey</button>
        </div>
      </div>
    </div>
    <div class="modal" id="ModalUsers" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"></h3>
          </div>
          <div class="modal-body">
            <table class="table table-bordered" id="surveys-table">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in survey_users">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>
</template>
<script>
import { FormClass } from "./Form";

export default {
  name: "survey",
  data() {
    return {
      view: "list",
      surveys: [],
      survey_users: [],
      users: [],
      currentSurvey: null,
      newForm: new FormClass({
        name: "",
        users: []
      })
    };
  },
  methods: {
    surveyForm() {
      this.view = "form";
    },
    editForm(survey) {
      this.view = "edit";
      axios.get("/surveys/" + survey.id + "/edit").then(response => {
        this.currentSurvey = response.data;
      });
    },
    createSurvey() {
      axios.post("/surveys", this.newForm.get()).then(response => {
        this.view = "list";
        this.getList();
      });
    },
    updateSurvey() {
      axios
        .patch("/surveys/" + this.currentSurvey.id, {
          name: this.currentSurvey.name,
          users: _.map(this.currentSurvey.users, item => {
            return item.id;
          })
        })
        .then(response => {
          this.view = "list";
          this.getList();
        });
    },

    deleteSurvey(id) {
      axios.delete("/surveys/" + id).then(() => {
        this.getList();
      });
    },

    getList() {
      axios.get("surveys/data").then(response => {
        this.surveys = response.data.data;
      });
    },

    showUsers(id) {
      axios.get("/surveys/" + id + "/users").then(response => {
        this.survey_users = response.data;
        $('#ModalUsers').modal('show')
      });
    },

    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: (loading, search, vm) => {
      axios
        .get("/users?q=" + search)
        .then(response => {
          vm.users = _.map(response.data, item => {
            item.label = item.name;
            return item;
          });
        })
        .catch(errors => {})
        .then(() => {
          loading(false);
        });
    }
  },
  mounted() {
    this.getList();
  }
};
</script>