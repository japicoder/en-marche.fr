import React from 'react';
import { render } from 'react-dom';
import UserListDefinitionWidget from '../components/UserListDefinitionWidget';

export default (memberType, type, wrapperSelector, checkboxSelector, mainCheckboxSelector, delegateAccessUuid, api,
                postApplyCallback) => {
    render(
        <UserListDefinitionWidget
            memberType={memberType}
            type={type}
            checkboxSelector={checkboxSelector}
            mainCheckboxSelector={mainCheckboxSelector}
            api={api}
            delegatedAccessUuid={delegateAccessUuid}
            postApplyCallback={postApplyCallback}
        />,
        dom(wrapperSelector)
    );
};
