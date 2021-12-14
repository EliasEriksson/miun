import React, { Component } from 'react';

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {value: ''};
 
    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }
 
  handleChange(event) {
    this.setState({value: event.target.value});
  }
 
  handleSubmit(event) {
    alert('Du heter ' + this.state.value);
    event.preventDefault();
  }
 
  render() {
    return (
     <div>
       <h1>ReactJS Form</h1>
     <form onSubmit={this.handleSubmit}>
        <label>
          Namn:
          <input type="text" value={this.state.value} onChange={this.handleChange} />
        </label>
        <input type="submit" value="Submit" />
      </form>
    </div>  
    );
  }
}
 
export default App;

