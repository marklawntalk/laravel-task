
class ErrorsClass {
    constructor() {
      this.errors = {};
    }
  
    has(field) {
      return this.errors.hasOwnProperty(field);
    }
  
    set(errors) {
      this.errors = errors;
    }
  
    get(field) {
      if (this.errors[field]) return this.errors[field][0];
    }
  
    clear(field) {
      if (this.errors[field]) delete this.errors[field];
    }
  
    getMessages() {
      let messages = [];
      for (let field in this.errors) {
        messages.push(this.errors[field][0])
      }
  
      return messages;
    }
  
    reset() {
      this.errors = {};
    }
  
  }

export class FormClass {
    constructor(data) {
      this.set(data);
      this.errors = new ErrorsClass();
    }
  
    set(data) {
      for (let field in data) {
        this[field] = data[field];
      }
    }
  
    get() {
      let data = Object.assign({}, this);
  
      delete data["errors"];
  
      return data;
    }
  
    reset() {
      this.errors.reset();
  
      let keys = Object.keys(this);
  
      for (let field in keys) {
        if (keys[field] === "errors") continue;
  
        delete this[keys[field]];
      }
    }
  }