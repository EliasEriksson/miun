import React, { Component } from 'react';

class Welcome extends Component {
    
  constructor(props){
      super(props);
      this.state = {
          msg: "Mikael"
      };
      this.updateMsg = this.updateMsg.bind(this);
 }

  updateMsg() {
      this.setState({
          msg: "New Mikael"
      });
  }    

  render() {
      return (
         <div>
           <h1>Hello {this.state.msg}!</h1>
           <button onClick={this.updateMsg}>Click me!</button>
         </div>   
      )
  }
}

export default Welcome;