import React from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {Loader} from "./Loader";
import {RecipeData} from "../types";
import {RecipeSummary} from "./RecipeSummary";

export class Home extends React.Component<{}, {
    recipes: JSX.Element[], fetchedAll: boolean
}> {
    private fetching: boolean;
    private fetchMore: boolean;
    private nextApiPage: number | null;

    constructor(props: any) {
        super(props);
        this.state = {
            recipes: [],
            fetchedAll: false
        }
        this.fetching = false;
        this.fetchMore = true;
        this.nextApiPage = 1;
    }

    fetchContent = async () => {
        this.fetching = true;
        if (this.nextApiPage) {
            const data = await requestEndpoint<ApiResponse<RecipeData>>(`/recipes/?page=${this.nextApiPage}`);

            await this.setState(({
                recipes: [...this.state.recipes, ...data.docs.map((recipeData) => {
                    return (<RecipeSummary
                            key={recipeData._id}
                            data={recipeData}/>
                    );
                })]
            }));
            this.nextApiPage = data.nextPage;
            if (!this.nextApiPage) await this.setState(({fetchedAll: true}));
            this.fetching = false;
        }

    }

    handleViewportEnter = async () => {
        this.fetchMore = true;
        while (this.fetchMore && this.nextApiPage) {
            await this.fetchContent();
        }
    }

    handleViewportLeave = async () => {
        this.fetchMore = false;
    }

    render = () => {
        return (
            <main>
                <div>
                    {this.state.recipes}
                </div>
                {!this.state.fetchedAll ? <Loader
                    onEnterViewport={this.handleViewportEnter}
                    onLeaveViewport={this.handleViewportLeave}/> : null}
            </main>
        );
    }
}