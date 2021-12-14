import React, { Component } from 'react';
 
class App extends React.Component {
  constructor(props) {
    super(props);
    this.txtReff = React.createRef();
    this.focusToTxt= this.focusToTxt.bind(this);
  }
 
  focusToTxt() {
    // focus to text input 
     this.txtReff.current.focus();
  }
 
  render() {
    return (
      <div>
        <h1>ReactJS Refs</h1>
        <input
          type="text"
          ref={this.txtReff} />
        <input
          type="button"
          value="Set focus"
          onClick={this.focusToTxt}
        />
      </div>
    );
  }
}
export default App;

