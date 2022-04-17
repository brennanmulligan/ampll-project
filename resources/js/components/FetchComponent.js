import React from "react";
import ReactDOM from 'react-dom/client';

export default class FetchComponent extends React.Component {
    constructor(props) {
        super(props);
    }

    clickHandler = (props) => {
        let date = changeMonth(this.props.goForward);

        updateCalendar(date, "mode=refreshCalendar");
    }

    render() {
       return (
            <button id={this.props.id} onClick={() => this.clickHandler(this.props)}>
                {this.props.goForward ? "⇨" : "⇦"}
            </button>
        );
    }
}

const prev = ReactDOM.createRoot(document.getElementById("prevTh"));
prev.render(<FetchComponent id="prev" goForward={false} />);

const next = ReactDOM.createRoot(document.getElementById("nextTh"));
next.render(<FetchComponent id="next" goForward={true} />);