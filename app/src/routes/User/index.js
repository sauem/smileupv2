import React, {useEffect, useState} from "react";
import {Card, Button, Skeleton, Table, Modal, Form, Select, Tag, Input, Typography} from 'antd';
import {useDispatch, useSelector} from "react-redux";
import {
  CREATE_USER,
  DELETE_USER,
  GET_USER,
  PROFILE_CHANGE_PASSWORD,
  UPDATE_USER,
  USER_TOKEN_SET
} from "../../constants/ActionTypes";
import {DeleteOutlined, EditOutlined, ExclamationCircleFilled, PlusCircleOutlined} from "@ant-design/icons/lib/icons";


const User = () => {
  const dispatch = useDispatch();
  const [state, setState] = useState({
    modalVisible: false,
    modalTitle: '',
    isChangePassword: false
  });
  const {users, crPage, crLimit} = useSelector(({user}) => user);
  const {authUser} = useSelector(({auth}) => auth);
  const [form, passwordForm] = Form.useForm();
  const getUserList = (page) => {
    dispatch({
      type: GET_USER,
      payload: {
        page: page,
      },
    });
  };


  useEffect(() => {
    getUserList(crPage);
  }, []);
  const hideModal = () => {
    setState({
      ...state,
      ...{
        modalVisible: false,
        modalTitle: '',
        isChangePassword: false
      }
    })
  }
  const onFinishForm = user => {
    dispatch({
      type: !state.isChangePassword ? CREATE_USER : PROFILE_CHANGE_PASSWORD,
      payload: {
        user
      }
    });
    setState({
      ...state,
      ...{modalVisible: false}
    })
  }
  const createNewUser = () => {
    setState({
      ...state,
      ...{
        modalVisible: true,
        modalTitle: 'Tạo tài khoản'
      }
    });
    form.resetFields();
  }

  const updateUser = (user) => {
    setState({
      ...state,
      ...{
        modalVisible: false,
        modalTitle: '',
        isChangePassword: true
      }
    });
    form.setFieldsValue(user);
  }
  const delUser = (id) => {
    Modal.confirm({
      title: 'Chú ý',
      icon: <ExclamationCircleFilled/>,
      content: 'Xóa tài khoản này?',
      okText: 'Đồng ý',
      cancelText: 'Hủy',
      onOk: () => {
        dispatch({
          type: DELETE_USER,
          payload: {
            id
          }
        });
      }
    });
  }
  return (
    <div>
      <Card title={`Tài khoản`}
            extra={<Button onClick={createNewUser} size={"small"} icon={<PlusCircleOutlined/>}>Thêm tài khoản</Button>}>
        {users === null ? (<Skeleton active/>) :
          <Table
            dataSource={users}
            pagination={{
              hideOnSinglePage: true,
              current: crPage,
              pageSize: crLimit,
            }}
            rowKey={(item) => `u${item.id}`}
            onChange={(page) => getUserList(page)}
            columns={[

              {title: 'Email', dataIndex: 'email', key: 'email'},
              {title: 'Họ tên', dataIndex: 'full_name', key: 'full_name'},
              {title: 'SĐT', dataIndex: 'phone', key: 'phone'},
              {title: 'Ghi chú', dataIndex: 'note', key: 'note'},
              {
                title: 'Trạng thái', dataIndex: 'status', key: 'status',
                render: status => <Tag color={status ? 'green' : 'red'}>{status ? 'Active' : 'Deactive'}</Tag>
              },
              {
                title: 'Hành động', key: 'action', render: raw => {
                  return (
                    <div>
                      <Button disabled={authUser.id === raw.id} onClick={() => delUser(raw.id)} size={`small`}
                              type={`danger`}><DeleteOutlined/> Xoá</Button>
                      <Button onClick={() => updateUser(raw)} size={`small`}><EditOutlined/> Đặt lại mật khẩu</Button>
                    </div>
                  )
                }
              }
            ]}
          />
        }
      </Card>
      <Modal
        onCancel={hideModal}
        title={state.modalTitle}
        visible={state.modalVisible}
        footer={[
          <Typography.Text className={`text-note`} type={`danger`}>(*) thông tin bắt buộc</Typography.Text>,
          <Button htmlType={`submit`} form={'userForm'}>Save</Button>,
          <Button onClick={hideModal}>Cancel</Button>,
        ]}
      >
        <Form onFinish={onFinishForm} form={form} id={`userForm`}
              {...{
                labelCol: {
                  xs: {span: 24},
                  sm: {span: 6},
                },
                wrapperCol: {
                  xs: {span: 24},
                  sm: {span: 18},
                },
                labelAlign: 'left',
              }}
              layout={'horizontal'}
        >
          <Form.Item name={'id'} hidden={true}>
            <Input/>
          </Form.Item>
          <Form.Item
            label="Họ tên"
            name={'full_name'}
            rules={[{required: true, message: 'Họ tên bắt buộc!'}]}
          >
            <Input placeholder={'Nguyen Van A'}/>
          </Form.Item>
          <Form.Item
            label="Email"
            name={'email'}
            rules={[{required: true, message: 'Email đăng nhập bắt buộc!'}]}
          >
            <Input placeholder={'example@gmail.com'}/>
          </Form.Item>
          <Form.Item
            label="Phone"
            name={'phone'}
          >
            <Input placeholder={'0349991833'}/>
          </Form.Item>
          {!form.getFieldValue('id') ?
            (<Form.Item
              label="Mật khẩu"
              name={'password'}
              rules={[{min: 6, message: 'Mật khẩu có ít nhất 6 ký tự'}, {
                required: true,
                message: 'Mật khẩu bắt buộc!'
              }]}
            >
              <Input.Password placeholder={'******'}/>
            </Form.Item>)
            : null
          }

          <Form.Item
            label="Quyền quản trị"
            name={'role_name'}
            initialValue={'Admin'}
          >
            <Select>
              <Select.Option value={`Admin`}>Quản trị</Select.Option>
              <Select.Option value={`Sale`}>Nhân viên sale</Select.Option>
            </Select>
          </Form.Item>
          <Form.Item
            label="Status"
            name={'status'}
            initialValue={1}
          >
            <Select>
              <Select.Option value={1}>Active</Select.Option>
              <Select.Option value={0}>Deactive</Select.Option>
            </Select>
          </Form.Item>
          <Form.Item
            label="Note"
            name={'phone'}
          >
            <Input.TextArea placeholder={'Note user'}/>
          </Form.Item>
        </Form>
      </Modal>
      <Modal
        title={'Đặt lại mật khẩu'}
        visible={state.isChangePassword}
        onCancel={hideModal}
        footer={[
          <Typography.Text className={`text-note`} type={`danger`}>(*) thông tin bắt buộc</Typography.Text>,
          <Button htmlType={`submit`} form={'formPassword'}>Save</Button>,
          <Button onClick={hideModal}>Cancel</Button>,
        ]}
      >
        <Form form={passwordForm}
              {...{
                labelCol: {
                  xs: {span: 24},
                  sm: {span: 8},
                },
                wrapperCol: {
                  xs: {span: 24},
                  sm: {span: 16},
                },
                labelAlign: 'left',
              }}
              layout={'horizontal'}
              id={`formPassword`}
              onFinish={onFinishForm}
        >
          <Form.Item name={'id'} hidden={true}>
            <Input/>
          </Form.Item>
          <Form.Item
            label="Mật khẩu cũ"
            name={'olPassword'}
            rules={[{min: 6, message: 'Mật khẩu có ít nhất 6 ký tự'}, {
              required: true,
              message: 'Nhập mật khẩu cũ'
            }]}
          >
            <Input.Password placeholder={'******'}/>
          </Form.Item>
          <Form.Item
            label="Mật khẩu mới"
            name={'newPassword'}
            rules={[{min: 6, message: 'Mật khẩu mới có ít nhất 6 ký tự'}, {
              required: true,
              message: 'Mật khẩu mới'
            }]}
          >
            <Input.Password placeholder={'******'}/>
          </Form.Item>
          <Form.Item
            label="Xác nhận mật khẩu"
            name={'rePassword'}
            rules={[{
              required: true,
              message: 'Nhập mật khẩu xác nhận!'
            }]}
          >
            <Input.Password placeholder={'******'}/>
          </Form.Item>

        </Form>
      </Modal>
    </div>
  )
}

export default User;
