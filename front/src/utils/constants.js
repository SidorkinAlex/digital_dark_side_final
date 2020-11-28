// modules
export const MODULE = {
  DIGIT_TASK: 'DIGIT_TASK',
  HRPAC_TAGS: 'HRPAC_TAGS',
  HRPAC_COMMENTS: 'HRPAC_COMMENTS',
  DOCUMENTS: 'Documents',
};

// field params, types, ids
export const FIELD = {
  ID: {
    DESCRIPTION: 'description',
    NAME: 'name',
    SOURCE: 'source',
    DIGIT_PROJECT_NAME: 'digit_project_name',
    ASSIGNED_USER_NAME: 'assigned_user_name',
    TASK_MANAGER_NAME: 'task_manager_name',
    DIGIT_BLOCK_NAME: 'digit_block_name',
    DIGIT_SECTION_NAME: 'digit_section_name',
    DIGIT_WORKSHOP_NAME: 'digit_workshop_name',
    PRIORITY: 'priority',
    COMPLEXITY: 'complexity',
    STATUS: 'status',
    TYPE: 'type',
    DATE_PLAN: 'date_plan',
    DATE_FACT: 'date_fact'
  },
  TYPE: {
    ID: 'id',
    LINK: 'link',
    GROUP: 'group',
    DECIMAL: 'decimal',
    MULTIENUM: 'multienum',
    TEXT: 'text',
    RELATE: 'relate',
    CURRENCY_ID: 'currency_id',
    BOOL: 'bool',
    INT: 'int',
    ENUM: 'enum',
    VARCHAR: 'varchar',
    DATE: 'date',
    DATETIME: 'datetimecombo',
    NAME: 'name'
  },
  PARAM: {
    ID: 'id',
    ID_NAME: 'id_name',
    DISABLED: 'disabled',
    REQUIRED: 'required'
  }
};

// actions
export const ACTION = {
  EDIT_VIEW: 'EditView',
  DETAIL_VIEW: 'DetailView',
  POPUP: 'Popup',
  JSON_LIST: 'json_list',
  SAVE: 'Save',
  DELETE: 'Delete',
  SUBPANEL_JSON_DATA: 'subpanel_json_data',
  GET_CANDIDATE_TAGS: 'getCandidatesTags',
  ADD_TAG_TO_CANDIDATE: 'addTagToCandidate',
  CREATE_TAG: 'createTag',
  REMOVE_TAG_FROM_CANDIDATE: 'removeTagFromCandidate',
  GET_SUBPANEL_JSON_DATA: 'get_subpanel_json_data',
  DELETE_RELATIONSHIP: 'DeleteRelationship',
  SUBPANEL_VIEWER: 'SubPanelViewer'
};

// subpanels
export const SUBPANEL = {
  COMMENTS: 'hrpac_comments',
  DOCUMENTS: 'digit_task_documents_1',
  ASSIGNED_TASK_INFO: 'assigned_task_info'
};

export const SCROLL_VALUE = 90;