import React from 'react';
import './App.css';

class Header extends React.Component {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper">
                <header className="content"><h1>Den fantastiska React sidan!</h1></header>
            </div>
        );
    }
}


class Main extends React.Component {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper">
                <main className="content">Main</main>
            </div>
        );
    }
}


class Footer extends React.Component<{ repoLink: string }> {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper">
                <footer className="content">
                    <p>Elias Eriksson 🄯 | <a href={this.props.repoLink}>Repo</a></p>
                </footer>
            </div>
        );
    }
}


export default (
    <React.StrictMode>
        <div className="window">
            <Header/>
            <Main/>
        </div>
        <Footer
            repoLink="https://github.com/EliasEriksson/miun/tree/master/webbutveckling/JavascriptbaseradWebbutveckling/Moment4"
        />
    </React.StrictMode>
);