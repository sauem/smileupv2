import React from "react";
import {Route, Switch} from "react-router-dom";

import asyncComponent from "util/asyncComponent";
import {useSelector} from "react-redux";

const App = ({match}) => {
  const {menu} = useSelector((state) => state.common);
  return (
    <div className="gx-main-content-wrapper">
      <Switch>
        {menu.map((value, index) => (
          <Route
            exact={value.exact ? true : false}
            key={`ridx_${index}`}
            path={`${match.url}${value.path}`}
            component={asyncComponent(() => value.component)}
          />
        ))}
      </Switch>
    </div>
  );
}

export default App;
