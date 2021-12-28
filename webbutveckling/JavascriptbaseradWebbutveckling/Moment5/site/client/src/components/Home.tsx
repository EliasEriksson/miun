import React from "react";
import {Footer, Header} from "./margins";
import {requestEndpoint} from "../modules/requests";
import {RecipeData} from "./Recipe";
import {Loader} from "./Loader";


export class Home extends React.Component<{}, {
    recipes: JSX.Element[]
}> {
    private fetching: boolean;
    private nextApiPage: number | null;

    constructor(props: any) {
        super(props);
        this.state = {
            recipes: [],
        }
        this.fetching = false;
        this.nextApiPage = 1;
    }

    fetchContent = async () => {
        this.fetching = true;
        if (this.nextApiPage) {
            const [data, status] = await requestEndpoint<RecipeData>(`/recipes/?page=${this.nextApiPage}`);
            if (200 <= status && status < 300) {
                console.log(data.docs);
            }
        } else {
            this.fetching = false;
        }
    }

    render = () => {
        return (
            <div>
                <Header/>
                <main>
                    <div>

                    </div>
                    <Loader
                        onEnterViewport={this.fetchContent}
                    />
                </main>
                <Footer/>
            </div>
        );
    }
}