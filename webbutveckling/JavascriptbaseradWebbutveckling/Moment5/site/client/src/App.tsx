import React from 'react';
import {Routes, Route, useParams} from "react-router-dom";
import {Home} from "./components/Home";
import {Footer, Header} from "./components/margins";
import {Recipe} from "./components/Recipe";


const Recipes = () => {
    const params = useParams();
    console.log(params)
    return (
        <Routes>
            <Route path={"/:_id"} element={<Recipe _id={`${params._id}`}/>}/>
        </Routes>
    );
}

export const App = (props: {}) => {
    const params = useParams();
    return (
        <>
            <Header/>
            <Routes>
                <Route path={"/"} element={<Home/>}>
                    <Route path={"recipes"}>
                        <Route path={":_id"} element={<Recipe _id={`${params._id}`}/>}/>
                    </Route>
                </Route>
            </Routes>
            <Footer/>
        </>
    );
}

// export class App extends React.Component<{}, {
//     page: JSX.Element
// }> {
//     constructor(props: {}) {
//         super(props);
//
//         this.state = {
//             page: <Home/>
//         }
//     }
//
//     navigate = (page: JSX.Element) => {
//         this.setState(({
//             page: page
//         }));
//     }
//
//     render = () => {
//         return (
//             <>
//                 <Header/>
//                 <Routes>
//                     <Route path={"/"} element={<Home/>}/>
//                     <Route path={"/recipes/*/:id"} element={<Recipes/>}/>
//                 </Routes>
//                 <Footer/>
//             </>
//         );
//     }
// }