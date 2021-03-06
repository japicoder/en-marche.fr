import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { EditorState, ContentState, convertToRaw } from 'draft-js';
import { Editor } from 'react-draft-wysiwyg';
import draftToHtml from 'draftjs-to-html';
import htmlToDraft from 'html-to-draftjs';
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css';
import linkIcn from '../../img/icn_link.svg';

// see https://jpuri.github.io/react-draft-wysiwyg/#/docs for more about this
const initialToolbar = {
    options: ['inline', 'list', 'link'],
    inline: {
        className: 'text-editor__toolbar__group',
        options: ['bold', 'italic', 'underline'],
    },
    list: {
        className: 'text-editor__toolbar__group',
        options: ['unordered', 'ordered'],
    },
    textAlign: {
        className: 'text-editor__toolbar__group',
        options: ['left', 'center', 'right'],
    },
    link: {
        className: 'text-editor__toolbar__group',
        popupClassName: 'text-editor__toolbar__link-modal',
        options: ['link'],
        defaultTargetOption: '_blank',
        link: { icon: linkIcn },
    },
    // pass an empty array as options to disable type related shortcut (shortcuts are activated by default even if button not shown)
    blockType: { options: [] },
};

/**
 * Custom WYSIWYG editor based on react-draft-wysiwyg and draft-js
 * See more https://jpuri.github.io/react-draft-wysiwyg
 */
class TextEditor extends React.Component {
    static getEditorStateFromContent(content) {
        if (content) {
            const blocksFromHtml = htmlToDraft(content);
            const { contentBlocks, entityMap } = blocksFromHtml;
            const contentState = ContentState.createFromBlockArray(contentBlocks, entityMap);
            return EditorState.createWithContent(contentState);
        }
        return EditorState.createEmpty();
    }

    constructor(props) {
        super(props);
        const initialEditorState = TextEditor.getEditorStateFromContent(props.initialContent);
        const initialTextContent = initialEditorState.getCurrentContent().getPlainText();
        this.state = {
            editorState: initialEditorState,
            textContent: initialTextContent,
        };
        this.onEditorStateChange = this.onEditorStateChange.bind(this);
        this.handleBeforeInput = this.handleBeforeInput.bind(this);
        this.handlePastedText = this.handlePastedText.bind(this);
    }

    handleBeforeInput() {
        const { maxLength } = this.props;
        const contentState = this.state.editorState.getCurrentContent();
        const contentText = contentState.getPlainText();
        // if text longer than maxLength, prevent Editor from adding new character
        if (maxLength && contentText.length >= maxLength) {
            this.onEditorStateChange(this.state.editorState);
            return 'handled';
        }
        // otherwise, defer to draft-js
        return 'not-handled';
    }

    // Uncomment to handle pasted text if longer than maxLength
    handlePastedText(pastedText) {
        const { maxLength } = this.props;
        const contentState = this.state.editorState.getCurrentContent();
        const contentText = contentState.getPlainText();

        if (maxLength && contentText.length + pastedText.length > maxLength) {
            return 'handled';
        }
        return 'not-handled';
    }

    onEditorStateChange(editorState) {
        // convert content state to html and text
        const contentState = editorState.getCurrentContent();
        const htmlContent = draftToHtml(convertToRaw(contentState));
        const textContent = contentState.getPlainText();
        // update state and send data
        // don't send html if text is empty
        const contentToSend = textContent ? htmlContent : textContent;
        this.setState({ editorState, textContent }, () => this.props.onChange(contentToSend, textContent));
    }

    render() {
        return (
            <div className="text-editor__wrapper">
                <div className={classNames('text-editor', { 'text-editor--error': !!this.props.error })}>
                    <Editor
                        editorState={this.state.editorState}
                        placeholder={this.props.placeholder}
                        toolbar={this.props.toolbar}
                        editorClassName="text-editor__content"
                        toolbarClassName="text-editor__toolbar"
                        handleBeforeInput={this.handleBeforeInput}
                        handlePastedText={this.handlePastedText}
                        onEditorStateChange={this.onEditorStateChange}
                        localization={{
                            locale: 'fr',
                            translations: {
                                'components.controls.list.unordered': 'Liste désordonnée',
                                'components.controls.list.ordered': 'Liste ordonnée',
                                'components.controls.link.linkTitle': 'Nom de votre lien',
                                'components.controls.link.linkTarget': 'Votre lien url',
                                'components.controls.link.link': 'Insérer un lien',
                            },
                        }}
                    />
                    {this.props.maxLength && (
                        <div className="text-editor__count">{`${this.state.textContent.length}/${
                            this.props.maxLength
                        }`}</div>
                    )}
                </div>
                {this.props.error && <p className="text-editor__error">{this.props.error}</p>}
            </div>
        );
    }
}

TextEditor.defaultProps = {
    error: '',
    initialContent: '',
    maxLength: undefined,
    placeholder: '',
    toolbar: initialToolbar,
};

TextEditor.propTypes = {
    error: PropTypes.string,
    initialContent: PropTypes.string, // html string
    maxLength: PropTypes.number,
    onChange: PropTypes.func.isRequired,
    placeholder: PropTypes.string,
    toolbar: PropTypes.object,
};

export default TextEditor;
