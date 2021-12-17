import React, {ChangeEvent, FormEvent} from 'react';

const rollDice = (numberOfDice: number, sides: number): number => {
    let sum = 0;
    for (let i = 0; i < numberOfDice; i++) {
        sum += Math.floor(Math.random() * (sides - 1 + 1) + 1);
    }
    return sum;
}

class Header extends React.Component {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper header">
                <header className="content">
                    <h1>Den fantastiska React sidan!</h1>
                </header>
            </div>
        );
    }
}

class Footer extends React.Component<{ repoLink: string }> {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper footer">
                <footer className="content">
                    <p>Elias Eriksson 🄯 | <a target="_blank" rel="noreferrer" href={this.props.repoLink}>Repo</a></p>
                </footer>
            </div>
        );
    }
}

class NumberInput extends React.Component<{ label: string, defaultNumber?: number }, { number: number }> {
    constructor(props: { label: string, defaultNumber?: number }) {
        super(props);
        this.state = {
            number: props["defaultNumber"] ?? 0
        }
    }

    onChange = (event: ChangeEvent<HTMLInputElement>) => {
        this.setState(() => ({
            number: parseInt(event.target.value)
        }));
    }

    render = (): JSX.Element => {
        return (
            <label>
                {this.props.label}
                <input onChange={e => {
                    this.onChange(e)
                }} type="number" value={this.state.number}/>
            </label>
        );
    }
}

class SubmitInput extends React.Component<{ label: string }> {
    render = (): JSX.Element => {
        return (
            <input type="submit" value={this.props.label}/>
        );
    }
}

class DiceRollForm extends React.Component<{}, { result?: number }> {
    private readonly numberOfDice: React.RefObject<NumberInput>;
    private readonly diceMaxValue: React.RefObject<NumberInput>;

    constructor(props: {}) {
        super(props);
        this.numberOfDice = React.createRef<NumberInput>();
        this.diceMaxValue = React.createRef<NumberInput>();
        this.state = {
            result: undefined
        }
    }

    onSubmit = (event: FormEvent) => {
        event.preventDefault();

        const numberOfDice = this.numberOfDice.current?.state.number ?? 0;
        const numberOfSides = this.diceMaxValue.current?.state.number ?? 0;
        if (numberOfDice > 0 && numberOfSides > 0) {
            this.setState(() => ({
                result: rollDice(
                    numberOfDice,
                    numberOfSides
                )
            }));
        }
    }

    render = (): JSX.Element => {
        if (this.state.result !== undefined) {
            return (
                <div>
                    <form onSubmit={event => this.onSubmit(event)}>
                        <NumberInput label={"Antal tärningar:"}
                                     defaultNumber={2}
                                     ref={this.numberOfDice}/>
                        <NumberInput label={"Antal sidor på vardera tärning:"}
                                     defaultNumber={6}
                                     ref={this.diceMaxValue}/>
                        <SubmitInput label={"Rulla tärningar"}/>
                    </form>
                    <p>Du rullade {this.numberOfDice.current?.state.number} tärningar
                        med {this.diceMaxValue.current?.state.number} sidor och fick summan {this.state.result}</p>
                </div>
            );
        }
        return (
            <div>
                <form onSubmit={event => this.onSubmit(event)}>
                    <NumberInput label={"Antal tärningar:"} defaultNumber={2} ref={this.numberOfDice}/>
                    <NumberInput label={"Antal sidor på vardera tärning:"} defaultNumber={6} ref={this.diceMaxValue}/>
                    <SubmitInput label={"Rulla tärningar"}/>
                </form>
            </div>
        );
    }
}

class Main extends React.Component {
    render = (): JSX.Element => {
        return (
            <div className="content-wrapper main">
                <main className="content">
                    <DiceRollForm/>
                </main>
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