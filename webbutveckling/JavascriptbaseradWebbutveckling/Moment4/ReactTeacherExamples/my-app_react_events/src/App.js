import React, { Component } from 'react';
 
class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {isToggle: true};
    this.handleClick = this.handleClick.bind(this);
  }
 
  handleClick() {
    this.setState(state => ({
      isToggle: !state.isToggle
    }));
  }
 
  render() {
    return (
      <div>
      <h1>ReactJS Events</h1>  
      <h3>{this.state.isToggle ? 'On' : 'Off'}</h3>
      <button onClick={this.handleClick}>
        Click Me
      </button>
      </div>
    );
  }
}
export default App;
