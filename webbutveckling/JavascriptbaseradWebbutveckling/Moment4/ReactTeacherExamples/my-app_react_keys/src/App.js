import React, { Component } from 'react';
 
class App extends React.Component {
   constructor() {
      super();
 
      this.state = {
         employees:[
            {
               empName: 'Mikael',
               id: 1
            },
            {
               empName: 'Mattias',
               id: 2
            },
            {
               empName: 'Jan-Erik',
               id: 3
            }
         ]
      }
   }
   render() {
      return (
         <div>
           <h1>React Keys</h1>
            <div>
               {this.state.employees.map((data, i) => <Employee 
                  key = {i} empData = {data} />)}
            </div>
         </div>
      );
   }
}
class Employee extends React.Component {
   render() {
      return (
         <div>
            <div>{this.props.empData.empName.toUpperCase()}</div>
            <div>{this.props.empData.id}</div>

            <br />
         </div>
      );
   }
}
export default App;
